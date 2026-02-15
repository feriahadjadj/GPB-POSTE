<?php

namespace App\Http\Controllers;

use App\Avancement;
use App\Nature;
use App\Notifications\userNotification;
use App\Projet;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $users = User::all();
        $projets = Projet::all();

        if (request()->has('id') && request()->input('id') != "undefined") {

            $id = request()->input('id');
        } else {
            if (Auth::user()->roles->contains('name', 'user')) {
                $id = Auth::user()->id;
            } else {
                $id = 'all';
            }
        }
        if (request()->has('year') && request()->input('year') != "undefined") {

            $year = request()->input('year');
        } else {
            $year = Carbon::today()->year;
        }

        // $id is now a wilaya code OR "all"
        $userIds = [];

        if ($id !== 'all' && $id !== 'undefined' && $id !== null && $id !== '') {
            $userIds = User::where('nbWilaya', $id)->pluck('id')->toArray();
        }

        // ✅ NEW: amountKey (for finance chart)
        $allowedAmountKeys = ['montantAlloue', 'montantEC', 'montantPC'];

        $amountKey = request()->input('amountKey', 'montantAlloue'); // default

        if (!in_array($amountKey, $allowedAmountKeys, true)) {
            $amountKey = 'montantAlloue';
        }
        $month = ['janvier' => 'jan', 'février' => 'feb', 'mars' => 'march', 'avril' => 'apr', 'mai' => 'may', 'juin' => 'jun', 'juillet' => 'jul', 'août' => 'aug', 'septembre' => 'sep', 'octobre' => 'oct', 'novembre' => 'nov', 'décembre' => 'dec'];

        if (Auth::user()->roles->contains('name', 'superA')) {

            $this->notificationPNL($projets, Auth::user(), 40);
        }

        if (Auth::user()->roles->contains('name', 'user')) {

            $this->notificationUPW(Auth::user(), 25);
        }
        $financeByNature = $this->financeByNature($id, $year, $amountKey, $userIds);
        $amountsSummary = $this->amountsSummary($id, $year, $userIds);
        //dd($this->mapData($year));

        /*dd([
            'id' => $id,
            'year' => $year,
            'userIdsCount' => count($userIds),
            'userIdsSample' => array_slice($userIds, 0, 5),
            'etats' => $this->doughnutData($id, $year, $userIds),
        ]);*/

        return view('home')->with([
            'id' => $id,
            'year' => $year,
            'month' => $month,
            'etats' => $this->doughnutData($id, $year, $userIds),
            'bar' => $this->getBarChartInfo($id, $year, $userIds),
            'ratio' => $this->getDeliverRatio($id, $year, $userIds),
            'table' => $this->tableData($id, $year, $userIds),
            'map' => $this->mapData($year),
            'amountKey' => $amountKey,
            'amountsSummary' => $amountsSummary,
            'financeByNature' => $financeByNature
        ]);
    }

    public function notificationPNL($projets, $user, $days)
    {

        foreach ($projets as $p) {
            if ($p->etatPhysique != "A") {
                $lastDate = Carbon::parse($p->avancement->first()['created_at']);

                $diffDate = $lastDate->diffInDays(Carbon::now()) - $this->delayDays($p);

                $upw = User::find($p->user_id);
                $upwName = $upw ? $upw->name : 'Utilisateur inconnu';
                $text = 'L\'UPW ' . $upwName . ' n\'a pas mis a jour le projet ' . $p->designation . ' depuis ' . $lastDate->toDateString() . '.';

                if ($diffDate >= $days) {

                    /* if notifications exist deja*/

                    if (!$this->NotifExist($text)) {

                        // foreach ($users as $user) {

                        if ($user->roles->contains('name', 'superA') && $user->email != 'djemmal@namane.dz') { // compte super admin masequer

                            // $user->notify(new userNotification(Auth::user(), $p, 'Retard', $text));
                            $user->notify(new userNotification(User::find($p->user_id), $p, 'Retard', $text));
                        }
                        // }
                    }
                }
                # code...
            }
        }
    }

    public function notificationUPW($user, $days)
    {
        $projets = $user->projet->all();
        foreach ($projets as $p) {
            if ($p->etatPhysique != "A") {
                $lastDate = Carbon::parse($p->avancement->first()['created_at']);
                $diffDate = $lastDate->diffInDays(Carbon::now()) - $this->delayDays($p);
                $text = 'Vous n\'avez pas mis a jour le projet ' . $p->designation . ' depuis ' . $lastDate->toDateString() . '.';

                if ($diffDate >= $days) {

                    /* if notifications exist deja*/

                    if (!$this->NotifExist($text)) {
                        $user->notify(new userNotification($user, $p, 'Retard', $text));
                    }
                }
            }
        }
        # code...
    }


    public function NotifExist($text)
    {

        $notifications = Auth::user()->Notifications()->get();

        foreach ($notifications as $N) {


            if ($N->data["text"] == $text) {

                return true;
            }
        }
        return false;
    }

    public function delayDays(Projet $p)
    {
        $days = 0;
        $retards = $p->retards()->where('type', 'realisation')->orderBy('date_reprise', 'desc')->get();

        if ($retards != "[]") {
            $r = $retards->first();

            $arret = Carbon::parse($p->avancement->first()['created_at']);
            $reprise = Carbon::parse($r->date_reprise);
            $days = $arret->diffInDays($reprise);
        }

        return $days;
    }

    public function getBarChartInfo($id, $year, $userIds = [])
    {
        if ($id == 'all' || empty($userIds)) {
            $a = ' and ';
        } else {
            $a = 'and users.id IN (' . implode(',', $userIds) . ') and ';
        }


        $projets = DB::select(DB::raw("select DATEPART(month,projets.dateMiseEnOeuvre) as nbMois ,DATENAME(month,projets.dateMiseEnOeuvre) as mois, count(projets.id) as nbProjet from projets, users,roles,role_user where  YEAR(projets.dateMiseEnOeuvre)='" . $year . "' " . $a . " roles.status=1 and roles.name='user' and users.id=role_user.user_id and  roles.id=role_user.role_id AND projets.user_id=users.id AND projets.etatPhysique='A'  group by DATEPART(month,projets.dateMiseEnOeuvre),DATENAME(month,projets.dateMiseEnOeuvre) order by  DATEPART(month,projets.dateMiseEnOeuvre) ASC ;"));


        $mois = DB::select(DB::raw("SELECT DATENAME(MONTH, DATEADD(MM, s.number, CONVERT(DATETIME, 0))) AS [MonthName]
                                  FROM master.dbo.spt_values s
                                  WHERE [type] = 'P' AND s.number BETWEEN 0 AND 11"));

        foreach ($mois as $key => $value) {
            $month[$key] = $mois[$key]->MonthName;
            # code...
        }

        $month = array_fill_keys(array_keys(array_flip($month)), 0);

        foreach ($projets as $key => $p) {
            if (array_key_exists($p->mois, $month)) {
                $month[$p->mois] = $p->nbProjet;
            }
        }

        return $month;
    }

    public function getDeliverRatio($id, $year, $userIds = [])
    {
        $natures = Nature::orderBy('id', 'ASC')->get();

        if ($id == 'all' || empty($userIds)) {
            $a = ' ';
        } else {
            $a = 'and users.id IN (' . implode(',', array_map('intval', $userIds)) . ') ';
        }

        $sql = "
        select
            projets.nature as nature,
            projets.etatPhysique as etatPhysique,
            count(*) as count
        from projets, role_user, users, roles
        where
            (projets.dateMiseEnOeuvre IS NULL OR YEAR(projets.dateMiseEnOeuvre) = '$year')
            and (
                YEAR(projets.odsEtude) <= '$year'
                OR (projets.odsEtude IS NULL AND YEAR(projets.created_at) = '$year')
            )
            and roles.status = 1
            $a
            and roles.name = 'user'
            and users.id = role_user.user_id
            and roles.id = role_user.role_id
            AND projets.user_id = users.id
        group by projets.nature, projets.etatPhysique;
    ";

        $total = DB::select(DB::raw($sql));

        $r = [];

        foreach ($natures as $n) {
            $r['NL'][$n->name] = 0;
            $r['R'][$n->name]  = 0;
            $r['A'][$n->name]  = 0;

            foreach ($total as $t) {
                if ($n->name == $t->nature) {
                    if ($t->etatPhysique != 'A' && $t->etatPhysique != 'R') {
                        $r['NL'][$n->name] += (int) $t->count;
                    } else {
                        $r[$t->etatPhysique][$n->name] = (int) $t->count;
                    }
                }
            }
        }

        return $r;
    }

    public function doughnutData($id, $year, $userIds = [])
    {
        $etat = ['E', 'P', 'R', 'NL', 'A'];

        if ($id == 'all' || empty($userIds)) {
            $a = ' ';
        } else {
            $a = 'and users.id IN (' . implode(',', array_map('intval', $userIds)) . ') ';
        }

        $sql = "
        select projets.etatPhysique as etatPhysique, count(*) as count
        from projets, role_user, users, roles
        where
            (projets.dateMiseEnOeuvre IS NULL OR YEAR(projets.dateMiseEnOeuvre) = '$year')
            and (
                YEAR(projets.odsEtude) <= '$year'
                OR (projets.odsEtude IS NULL AND YEAR(projets.created_at) = '$year')
            )
            and roles.status = 1
            $a
            and roles.name = 'user'
            and users.id = role_user.user_id
            and roles.id = role_user.role_id
            AND projets.user_id = users.id
        group by projets.etatPhysique;
        ";

        $total = DB::select(DB::raw($sql));

        $r = [];
        foreach ($etat as $e) $r[$e] = 0;

        foreach ($total as $t) {
            if (isset($r[$t->etatPhysique])) {
                $r[$t->etatPhysique] = (int) $t->count;
            }
        }

        return $r;
    }

    public function tableData($id, $year, $userIds = [])
    {
        $natures = Nature::orderBy('id', 'ASC')->get();

        if ($id == 'all' || empty($userIds)) {
            $a = ' ';
        } else {
            $a = 'and users.id IN (' . implode(',', array_map('intval', $userIds)) . ') ';
        }

        $sql = "
        select
            projets.nature as nature,
            projets.etatPhysique as etatPhysique,
            count(*) as count
        from projets, role_user, users, roles
        where
            (projets.dateMiseEnOeuvre IS NULL OR YEAR(projets.dateMiseEnOeuvre) = '$year')
            and (
                YEAR(projets.odsEtude) <= '$year'
                OR (projets.odsEtude IS NULL AND YEAR(projets.created_at) = '$year')
            )
            and roles.status = 1
            $a
            and roles.name = 'user'
            and users.id = role_user.user_id
            and roles.id = role_user.role_id
            AND projets.user_id = users.id
        group by projets.nature, projets.etatPhysique;
    ";

        $total = DB::select(DB::raw($sql));

        $r = [];

        $r['A']['total'] = 0;
        $r['R']['total'] = 0;
        $r['NL']['total'] = 0;
        $r['total']['total'] = 0;

        foreach ($natures as $n) {

            $r['NL'][$n->name] = 0;
            $r['R'][$n->name]  = 0;
            $r['A'][$n->name]  = 0;

            $r['NL']['text'] = 'Projets non lancés, en étude et en procedure (NL+E+P)';
            $r['R']['text']  = 'Projets en cours de réalisation  ( R ) ';
            $r['A']['text']  = 'Projets achevés (A)';
            $r['total']['text'] = 'TOTAL';

            foreach ($total as $t) {

                if ($n->name == $t->nature) {

                    if ($t->etatPhysique != 'A' && $t->etatPhysique != 'R') {
                        $r['NL'][$n->name] += (int) $t->count;
                    } else {
                        $r[$t->etatPhysique][$n->name] = (int) $t->count;
                    }
                }
            }

            $r['total'][$n->name] = $r['NL'][$n->name] + $r['R'][$n->name] + $r['A'][$n->name];
            $r['total']['total'] += $r['total'][$n->name];

            $r['NL']['total'] += $r['NL'][$n->name];
            $r['A']['total']  += $r['A'][$n->name];
            $r['R']['total']  += $r['R'][$n->name];
        }

        if ($r['total']['total'] != 0) {
            $r['NL']['taux'] = round($r['NL']['total'] * 100 / $r['total']['total'], 2) . " %";
            $r['A']['taux']  = round($r['A']['total']  * 100 / $r['total']['total'], 2) . " %";
            $r['R']['taux']  = round($r['R']['total']  * 100 / $r['total']['total'], 2) . " %";
        } else {
            $r['NL']['taux'] = "0 %";
            $r['A']['taux']  = "0 %";
            $r['R']['taux']  = "0 %";
        }

        $r['total']['taux'] = "100 %";

        return $r;
    }


    public function mapData($year)
    {
        $projets = DB::select(DB::raw("select users.name as name, users.id as id, users.nbWilaya  ,nb=ISNULL(x.nb,0)
        from roles,role_user,users
        left join (select u.id as id ,nb=count(projets.id) from projets,users u where YEAR(projets.dateMiseEnOeuvre)='" . $year . "' and projets.etatPhysique='A' And u.id=projets.user_id group by u.id)x ON x.id=users.id
        where roles.status=1 and  users.id=role_user.user_id and  roles.id=role_user.role_id and roles.name ='user' and users.id is not null group by users.name,users.id, users.nbWilaya ,x.nb;
        "));
        foreach ($projets as $p) {

            if ($p->nbWilaya != '00') {

                if ($p->nbWilaya == '16') {
                    $r[$p->nbWilaya]['name'] = '16-Alger';
                } else {
                    $r[$p->nbWilaya]['name'] = $p->name;
                }
                if (!isset($r[$p->nbWilaya]['id'])) {
                    $r[$p->nbWilaya]['id'] = $p->id;
                }


                if (isset($r[$p->nbWilaya]['nb'])) {
                    $r[$p->nbWilaya]['nb'] += $p->nb;
                } else {
                    $r[$p->nbWilaya]['nb'] = $p->nb;
                }
            }

            # code...
        }
        //dd($r);


        return $r;
    }

    public function financeByNature($id, $year, $amountKey, $userIds = [])
    {
        $allowed = ['montantAlloue', 'montantEC', 'montantPC'];
        if (!in_array($amountKey, $allowed, true)) {
            $amountKey = 'montantAlloue';
        }

        // ✅ base query: all projects
        $q = \App\Projet::query();

        // ✅ apply wilaya filter only if a real id is provided
        if ($id !== 'all' && !empty($userIds)) {
            $q->whereIn('user_id', $userIds);
        }

        // ✅ apply same year logic you use elsewhere
        $q->where(function ($x) use ($year) {
            $x->whereYear('dateMiseEnOeuvre', $year)
                ->orWhereNull('dateMiseEnOeuvre');
        });

        $q->where(function ($x) use ($year) {
            $x->whereYear('odsEtude', '<=', $year)
                ->orWhere(function ($y) use ($year) {
                    $y->whereNull('odsEtude')
                        ->whereYear('created_at', '=', $year);
                });
        });

        // ✅ group by nature and sum the chosen amount
        $rows = $q->selectRaw("nature, SUM($amountKey) as total")
            ->groupBy('nature')
            ->orderBy('nature')
            ->get();

        // ✅ return as associative array: ['Construction' => 123, ...]
        $result = [];
        foreach ($rows as $r) {
            $result[$r->nature] = (float) ($r->total ?? 0);
        }

        return $result;
    }

    public function amountsSummary($id, $year, $userIds = [])
    {
        // Base query (same year logic you use in other methods)
        $q = \App\Projet::query();

        // Filter by wilaya (UPW) if not all
        if ($id !== 'all' && !empty($userIds)) {
            $q->whereIn('user_id', $userIds);
        }

        // Keep same year logic you already use
        $q->where(function ($x) use ($year) {
            $x->whereYear('dateMiseEnOeuvre', $year)
                ->orWhereNull('dateMiseEnOeuvre');
        });

        $q->where(function ($x) use ($year) {
            $x->whereYear('odsEtude', '<=', $year)
                ->orWhere(function ($y) use ($year) {
                    $y->whereNull('odsEtude')
                        ->whereYear('created_at', '=', $year);
                });
        });

        // Sum the three amounts
        $row = $q->selectRaw("
            COALESCE(SUM(montantAlloue), 0) as montantAlloue,
            COALESCE(SUM(montantEC), 0) as montantEC,
            COALESCE(SUM(montantPC), 0) as montantPC
        ")
            ->first();

        return [
            'montantAlloue' => (float) $row->montantAlloue,
            'montantEC'     => (float) $row->montantEC,
            'montantPC'     => (float) $row->montantPC,
        ];
    }
}
