@extends('layouts.poste')
@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    /* ===== MODERN CSS VARIABLES ===== */
    :root {
        --primary: #4D95FE; /* Requested Blue */
        --accent: #FDC90A;  /* Requested Yellow */
        --primary-hover: #3a84ec;
        --bg-main: #f8fafc;
        --card-bg: #ffffff;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        --success: #16a34a;
        --warning: #d97706;
        --danger: #dc2626;
    }

    body {
        background: var(--bg-main);
        font-family: 'Inter', sans-serif;
        color: var(--text-main);
    }

    .page-container {
        background: var(--card-bg);
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 4px 25px rgba(0,0,0,0.05);
        margin: 20px auto;
        max-width: 98%;
        border: 1px solid var(--border-color);
    }

    /* ===== HEADER ===== */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 20px;
    }

    .page-header h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: var(--text-main);
        display: flex;
        align-items: center;
    }

    .header-actions {
        display: flex;
        gap: 12px;
    }

    /* ===== BEAUTIFUL THIN ICONS (NO BG) ===== */
    .icon-blue { color: var(--primary); background: none !important; }
    .icon-yellow { color: var(--accent); background: none !important; }
    
    .page-header h1 i {
        font-size: 28px;
        margin-right: 15px;
        font-weight: 300; /* Thin look */
    }

    /* ===== BUTTONS ===== */
    .btn-primary {
        background: var(--primary);
        color: #fff !important;
        padding: 10px 18px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        transition: 0.3s;
    }

    .btn-primary:hover { background: var(--primary-hover); transform: translateY(-2px); }

    .btn-secondary {
        background: #fff;
        border: 1px solid var(--border-color);
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 600;
        color: var(--text-main);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.3s;
    }
    .btn-secondary:hover { border-color: var(--primary); color: var(--primary); }

    /* ===== FILTER BAR ===== */
    .filter-bar {
        display: flex;
        gap: 20px;
        padding: 20px;
        background: #ffffff;
        border-radius: 12px;
        margin-bottom: 30px;
        border: 1px solid var(--border-color);
        align-items: flex-end;
        FONT-SIZE: small;
    }

    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group label { 
        font-size: 11px; 
        font-weight: 700; 
        color: var(--text-muted); 
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* ===== KPI CARDS ===== */
    .kpi-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .kpi {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 14px;
        padding: 22px;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: 0.3s;
    }
    
    .kpi:hover { border-color: var(--primary); box-shadow: 0 10px 20px rgba(77, 149, 254, 0.1); }

    .kpi-icon-box {
        background: none !important; /* Removed background */
        font-size: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .kpi span { font-size: 13px; color: var(--text-muted); font-weight: 500; }
    .kpi strong { font-size: 20px; display: block; margin-top: 2px; }

    /* ===== TABS ===== */
    .tabs-pro {
        display: flex;
        gap: 8px;
        margin-bottom: 25px;
        border-bottom: 1px solid var(--border-color);
    }

    .tab-btn {
        padding: 12px 24px;
        border: none;
        background: none;
        cursor: pointer;
        font-weight: 600;
        color: var(--text-muted);
        border-bottom: 3px solid transparent;
        transition: 0.2s;
    }

    .tab-btn.active {
        border-color: var(--accent); /* Yellow accent for active tab underline */
        color: var(--primary);
    }

    /* ===== TABLE ===== */
    .tab-content { display: none; }
    .tab-content.active { display: block; }

    .table-wrapper {
        border-radius: 12px;
        border: 1px solid var(--border-color);
        overflow: hidden;
    }

    .pro-table { width: 100%; border-collapse: collapse; }

    .pro-table thead th {
        background: #fcfdfe;
        padding: 16px 14px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        font-size: 13px;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }

    .pro-table td { padding: 16px 14px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
    .pro-table tr:hover { background-color: #f8fbff; }

    /* ===== PROGRESS BAR ===== */
    .progress-wrap { display: flex; align-items: center; gap: 10px; }
    .progress { width: 80px; height: 6px; background: #e2e8f0; border-radius: 10px; overflow: hidden; }
    .progress-bar { height: 100%; background: var(--primary); }

    /* ===== BADGES ===== */
    .badge { padding: 5px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; background: #f1f5f9; color: var(--text-muted); }

    .deadline i { margin-right: 5px; }
    .deadline.ok { color: var(--success); }
    .deadline.soon { color: var(--warning); }
    .deadline.late { color: var(--danger); }

    /* ===== MODAL ===== */
    .modal-overlay {
        position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(4px);
        display: none; align-items: center; justify-content: center; z-index: 9999;
    }
    .modal-box { background: #fff; width: 700px; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
    .modal-header { padding: 20px; background: #fff; display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-color); }
    .modal-body { padding: 24px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
    .modal-item { background: #f8fafc; padding: 15px; border-radius: 10px; border: 1px solid #f1f5f9; }
    .modal-item span { display: block; font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px; }
    .modal-item strong { color: var(--text-main); }

    @media print {
        .filter-bar, .tabs-pro, .header-actions, .btn-inline { display: none !important; }
        .page-container { box-shadow: none; border: none; padding: 0; }
        .tab-content { display: block !important; }
    }
    /* ===== BIGGER & MODERN ICONS ===== */
.page-header h1 i{
    font-size:32px !important;
    color:var(--primary);
    transition:0.3s;
}

.page-header h1 i:hover{
    transform:scale(1.15);
    color:var(--accent);
}

/* KPI icons bigger */
.kpi-icon-box i{
    font-size:30px !important;
    color:var(--primary);
    transition:all 0.3s ease;
}

/* yellow icons */
.icon-yellow{
    color:var(--accent) !important;
}

/* blue icons */
.icon-blue{
    color:var(--primary) !important;
}

/* ===== KPI CARD ANIMATION ===== */
.kpi{
    transition:all 0.35s ease;
    position:relative;
    overflow:hidden;
}

/* hover lift */
.kpi:hover{
    transform:translateY(-8px) scale(1.02);
    box-shadow:0 20px 35px rgba(77,149,254,0.15);
    border-color:var(--primary);
}

/* icon animation on hover */
.kpi:hover i{
    transform:scale(1.2) rotate(5deg);
    color:var(--accent);
}

/* subtle shine animation */
.kpi::before{
    content:"";
    position:absolute;
    top:0;
    left:-100%;
    width:100%;
    height:100%;
    background:linear-gradient(
        120deg,
        transparent,
        rgba(77,149,254,0.15),
        transparent
    );
    transition:0.6s;
}

.kpi:hover::before{
    left:100%;
}

/* ===== TITLES MORE VISIBLE ===== */
.page-header h1{
    font-size:28px !important;
    font-weight:800 !important;
}

.subtitle{
    font-size:14px !important;
    font-weight:500;
}

/* table icons */
.pro-table i{
    font-size:16px;
}
/* ===== TITLES COLOR: NOIR CHARBON ===== */
.page-header h1{
    color:#1a1a1a !important; /* noir charbon */
}

.page-header h1 span{
    color:#1a1a1a !important;
}

.subtitle{
    color:#2b2b2b !important;
}

/* table headers */
.pro-table thead th{
    color:#1a1a1a !important;
}

/* KPI titles */
.kpi span{
    color:#2b2b2b !important;
}
/* ===== FILTER TITLES ===== */
.filter-bar label{
    color:#1a1a1a !important;
    font-weight:700;
        FONT-SIZE: small;
}

/* select text */
.filter-bar select{
    color:#1a1a1a !important;
    font-weight:600;
}

/* selected option */
.filter-bar option{
    color:#1a1a1a;
}

/* ===== TABS TITLES (construction, rehabilitation...) ===== */
.tab-btn{
    color:#1a1a1a !important;
    font-weight:700;
    font-size:16px;
}

.tab-btn.active{
    color:#000 !important;
}

/* hover effect */
.tab-btn:hover{
    color:#000 !important;
}
/* Styles pour les badges de Phase */
.phase-badge {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    white-space: nowrap;
}
.phase-id { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
.phase-etude { background: #e0f2fe; color: #075985; border: 1px solid #bae6fd; }
.phase-real { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }

.etat-badge{
    display:inline-block;
    padding:5px 12px;
    border-radius:6px;
    font-size:11px;
    font-weight:700;
    background:#f1f5f9;
    color:#1e293b;
    line-height:1.2;
}

.etat-blue{
    background:#dbeafe;
    color:#1d4ed8;
}

.etat-green{
    background:#dcfce7;
    color:#166534;
}

.etat-orange{
    background:#ffedd5;
    color:#c2410c;
}

.etat-gray{
    background:#f1f5f9;
    color:#475569;
}

</style>

<div class="page-container">

    {{-- ================= HEADER ================= --}}
    <div class="page-header">
        <div>
            <h1>
               <span style="border-left: 3px solid var(--accent); padding-left: 15px;">Gestion des projets</span>
            </h1>
            <p class="subtitle" style="margin-left: 45px; color: var(--text-muted); font-size: 13px;">
                <i class="fa-solid fa-clock icon-yellow" style="margin-right: 5px;"></i> 
                Suivi analytique en temps réel
            </p>
        </div>

        <div class="header-actions">
            @can('upw-role')
                <a href="{{ route('projet.create') }}" class="btn-primary">
                    <i class="fa-regular fa-square-plus" style="font-size: 18px;"></i> Nouveau projet
                </a>
            @endcan
            @can('manage-users')
                <button class="btn-secondary" onclick="printAllProjects()">
                   <i class="fa-solid fa-print icon-blue"></i> Imprimer
                </button>
            @endcan
        </div>
    </div>

    {{-- ================= FILTER ================= --}}
    <div class="filter-bar">
        @can('manage-users')
        <div class="form-group">
            <label> Wilaya</label>
            <select name="wilaya" id="wilaya" class="select-filtre form-control">
                @foreach (App\User::all() as $user)
                    @if($user->roles->contains('name','user'))
                        <option value="{{ $user->id }}" {{ $user->id == ($id ?? auth()->id()) ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        @endcan

        <div class="form-group">
            <label>TYPE Financement</label>
            <select name="finance" id="finance" class="select-filtre form-control">
                <option value="tout">Tous les fonds</option>
                @foreach ($finances as $f)
                    <option value="{{$f->name}}" @if($f->name == $finance) selected @endif>{{$f->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label> Année</label>
            <select name="year" id="year" class="select-filtre form-control">
                @for ($i = (int)Carbon\Carbon::today()->year; $i > (int)Carbon\Carbon::today()->year-5; $i--)
                    <option value="{{$i}}" @if($i==$year) selected @endif>{{$i}}</option>
                @endfor
            </select>
        </div>

        @can('edit-users')
        <div class="form-group" style="margin-left:auto">
            <label><i class="fa-solid fa-chart-pie icon-yellow"></i> Vue de données</label>
            <select name="select-recap" id="select-recap" class="form-control" style="border-color: var(--border-color); min-width: 150px;">
                <option value="DUPW" {{ request()->is('projet/gestionprojets*') ? 'selected' : '' }}>DUPW Dashboard</option>
                <option value="1" {{ request()->is('projet/recaps/1*') ? 'selected' : '' }}>Récapitulatif 1</option>
                <option value="2" {{ request()->is('projet/recaps/2*') ? 'selected' : '' }}>Récapitulatif 2</option>
                <option value="3" {{ request()->is('projet/recaps/3*') ? 'selected' : '' }}>Récapitulatif 3</option>
                <option value="4" {{ request()->is('projet/recaps/4*') ? 'selected' : '' }}>Récapitulatif 4</option>
            </select>
        </div>
        @endcan
    </div>

    {{-- ================= TABS ================= --}}
    <div class="tabs-pro">
        @foreach($projetsFN as $i=>$type)
            <button class="tab-btn {{ $i==0?'active':'' }}" data-tab="tab-{{ $loop->index }}">
                {{ $type->name }}
            </button>
        @endforeach
    </div>

    {{-- ================= CONTENT ================= --}}
    @foreach($projetsFN as $i=>$type)
        @php
            $total = $type->count();
            $alloue = $type->sum('montantAlloue');
            $consomme = $type->sum('montantPC');
            $taux = $alloue > 0 ? round(($consomme / $alloue) * 100, 2) : 0;
        @endphp

        <div id="tab-{{ $loop->index }}" class="tab-content {{ $i==0?'active':'' }}">

            {{-- KPI --}}
            <div class="kpi-row">
                <div class="kpi">
                    <div class="kpi-icon-box"><i class="fa-solid fa-diagram-project icon-blue"></i></div>
                    <div><span>Projets Actifs</span><strong>{{ $total }}</strong></div>
                </div>
                <div class="kpi">
                    <div class="kpi-icon-box"><i class="fa-solid fa-sack-dollar icon-yellow"></i></div>
                    <div><span>Budget Alloué</span><strong>{{ number_format($alloue, 0, ',', ' ') }} <small style="font-size: 11px;">DZD</small></strong></div>
                </div>
                <div class="kpi">
                    <div class="kpi-icon-box"><i class="fa-solid fa-chart-line icon-blue"></i></div>
                    <div><span>Taux de Consommation</span><strong>{{ $taux }} %</strong></div>
                </div>
            </div>

            {{-- TABLE --}}
            <div class="table-wrapper">
                <table class="pro-table">
                    <thead>
                        <tr>
                            <th>Désignation</th>
                            <th>Phase</th> 
                            <th>Financement</th>
                            <th>Budget Alloué</th>
                            <th>Engagé</th>
                            <th>Payé</th>
                            <th><i class="fa-regular fa-hashtag"></i> ODS</th>
                            <th>État Physique</th>
                            <th>Avancement</th>
                            <th>Délai Restant</th>
                            <th style="text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($type as $p)
                            @php
                            $phaseLabel = "Identification";
                            $phaseClass = "phase-id";
                            $phaseIcon = "fa-magnifying-glass";

                            if ((int) $p->step === 3) {
                                $phaseLabel = "Réalisation";
                                $phaseClass = "phase-real";
                                $phaseIcon = "fa-person-digging";
                            } elseif ((int) $p->step === 2) {
                                $phaseLabel = "Étude";
                                $phaseClass = "phase-etude";
                                $phaseIcon = "fa-pen-ruler";
                            }
                            $today = \Carbon\Carbon::today();
                            $dateRec = $p->dateReception ? \Carbon\Carbon::parse($p->dateReception) : null;
                            @endphp
                            <tr>
                                <td>
                                    
                                    <a href="{{ route('projet.voirprojet',$p->id) }}" style="font-weight:600; text-decoration:none; color:var(--primary);">
                                        {{ Str::limit($p->designation, 45) }}
                                    </a>
                                </td>
                                <td>
                    <a href="{{ route('projet.edit', $p->id) }}" style="text-decoration:none;">
                        <span class="phase-badge {{ $phaseClass }}" style="cursor:pointer;">
                            <i class="fa-solid {{ $phaseIcon }}"></i> {{ $phaseLabel }}
                        </span>
                    </a>
</td>
                                <td style="color: var(--text-muted); font-size: 12px;">{{ $p->finance }}</td>
                                <td style="font-weight: 500;">{{ number_format($p->montantAlloue) }}</td>
                                <td>{{ number_format($p->montantEC) }}</td>
                                <td style="color:var(--success); font-weight:700;">{{ number_format($p->montantPC) }}</td>
                                <td><code style="color: var(--primary);">{{ $p->odsRealisation ?? '-' }}</code></td>
                                <td>
                                    @php
                                        $etat = $p->etatPhysiqueDisplay ?? $p->etatPhysique ?? '';

                                        if ($etat === 'R') {
                                            $label = 'En Cours';
                                            $class = 'etat-blue';
                                        } elseif ($etat === 'NL') {
                                            $label = 'Non Lancé';
                                            $class = 'etat-gray';
                                        } elseif ($etat === 'A') {
                                            $label = 'Achevé';
                                            $class = 'etat-green';
                                        } elseif ($etat === 'EA' || $etat === 'En Attente') {
                                            $label = 'En Attente';
                                            $class = 'etat-orange';
                                        } else {
                                            $label = $etat ?: '-';
                                            $class = 'etat-gray';
                                        }
                                    @endphp

                                    <span class="etat-badge {{ $class }}">{{ $label }}</span>
                                </td>
                                <td>
                                    <div class="progress-wrap">
                                        <div class="progress"><div class="progress-bar" style="width: {{ min($p->tauxA,100) }}%"></div></div>
                                        <span style="font-size:11px; font-weight:800; color: var(--text-main);">{{ $p->tauxA }}%</span>
                                    </div>
                                </td>
                                <td>
                                    @if($dateRec)
                                        @php $days = $today->diffInDays($dateRec,false); @endphp
                                        <div class="deadline {{ $days < 0 ? 'late' : ($days <= 30 ? 'soon' : 'ok') }}">
                                            <i class="fa-regular fa-hourglass-half"></i>
                                            <a href="{{ route('projet.voirprojet',$p->id) }}" style="text-decoration:none; color:inherit;">
                                                {{ $days < 0 ? abs($days).' j retard' : $days.' j' }}
                                            </a>
                                        </div>
                                    @else <span style="color:#cbd5e1;">-</span> @endif
                                </td>
                                <td style="text-align: right;">
                                    <button class="btn-secondary" style="padding:6px 12px;" onclick="openModal({{ $p->id }})">
                                       <i class="fa-solid fa-eye" style="color: var(--primary);"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="10" style="text-align:center; padding: 40px; color:var(--text-muted)">
                                <i class="fa-regular fa-folder-open" style="display:block; font-size: 30px; margin-bottom: 10px; color: #e2e8f0;"></i>
                                Aucun projet trouvé dans cette catégorie
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

</div>

{{-- MODAL AND SCRIPTS REMAIN FUNCTIONALLY THE SAME --}}
<script>
const projects = [
@foreach($projetsFN as $type)
    @foreach($type as $p)
        {
            id: {{ $p->id }},
            designation: @json($p->designation),
            finance: @json($p->finance),
            montantAlloue: @json($p->montantAlloue),
            montantEC: @json($p->montantEC),
            montantPC: @json($p->montantPC),
            tauxA: @json($p->tauxA),
            etatPhysique: @json($p->etatPhysique),
            odsRealisation: @json($p->odsRealisation),
            dateReception: @json($p->dateReception),
            observation: @json($p->observation),
        },
    @endforeach
@endforeach
];
</script>

<div id="projectModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <h3 style="margin:0; font-weight: 700; color: var(--text-main);"><i class="fa-regular fa-file-invoice icon-blue" style="margin-right: 10px;"></i> Détails du projet</h3>
            <button onclick="closeModal()" style="border:none; background:none; font-size:24px; cursor:pointer; color: var(--text-muted);">&times;</button>
        </div>
        <div class="modal-body" id="modalContent"></div>
        <div style="padding: 15px 24px; text-align: right; border-top: 1px solid var(--border-color);">
            <button class="btn-primary" onclick="closeModal()" style="padding: 8px 25px;">Fermer</button>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.tab-btn').forEach(btn=>{
    btn.addEventListener('click',()=>{
        document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c=>c.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById(btn.dataset.tab).classList.add('active');
    });
});

function openModal(id){
    const p = projects.find(pr => pr.id === id);
    if(!p){ return; }
    document.getElementById('modalContent').innerHTML = `
        <div class="modal-item" style="grid-column: span 2"><span>Désignation</span><strong>${p.designation}</strong></div>
        <div class="modal-item"><span>Financement</span><strong>${p.finance}</strong></div>
        <div class="modal-item"><span>Budget Alloué</span><strong>${p.montantAlloue} DZD</strong></div>
        <div class="modal-item"><span>Montant Engagé</span><strong>${p.montantEC} DZD</strong></div>
        <div class="modal-item"><span>Montant Payé</span><strong style="color:var(--success)">${p.montantPC} DZD</strong></div>
        <div class="modal-item"><span>Taux d’avancement</span><strong>${p.tauxA}%</strong></div>
        <div class="modal-item"><span>État physique</span><strong>${p.etatPhysique}</strong></div>
        <div class="modal-item" style="grid-column: span 2"><span>Observation</span><strong>${p.observation ?? 'Aucune observation'}</strong></div>
    `;
    document.getElementById('projectModal').style.display = 'flex';
}
function closeModal(){ document.getElementById('projectModal').style.display = 'none'; }

$(".select-filtre").change(function() {
    var w = $('#wilaya').val() ?? '{{$id}}';
    var f = $('#finance').val();
    var y = $('#year').val();
    location.href = "/projet/gestionprojets/"+w+"/"+f+"/"+y;
});

document.getElementById('select-recap')?.addEventListener('change', function () {
    const recap = this.value;
    const year  = document.getElementById('year').value;
    const wilaya = document.getElementById('wilaya')?.value ?? '{{ auth()->id() }}';
    window.location.href = recap === 'DUPW' ? `/projet/gestionprojets/${wilaya}/tout/${year}` : `/projet/recaps/${recap}/tout/${year}`;
});

function printAllProjects() {
    let original = document.body.innerHTML;

    // All possible categories (even if empty)
    const categories = [
        "construction",
        "rehabilitation",
        "amenagement",
        "etancheite",
        "logements d'astreinte"
    ];

    // Totals
    let globalAlloue = 0;
    let globalEngage = 0;
    let globalPaye   = 0;
    let globalStates = { E: 0, P: 0, R: 0, NL: 0, A: 0 };

    function normalizeString(str) {
        if (!str) return '';
        return str.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, '').trim();
    }

    let html = `
        <div style="margin-bottom:20px;">
            <img src="{{ asset('img/logo-head.png') }}" width="220">
        </div>

        <h3 style="text-align:left; margin-bottom:5px;">
            Liste des projets de l'upw {{ auth()->user()->name }} de l'année {{ $year }}
        </h3>
    `;

    categories.forEach(cat => {
        let filtered = projects.filter(p => normalizeString(p.type) === normalizeString(cat));

        let totalAlloue = 0;
        let totalEngage = 0;
        let totalPaye   = 0;
        let stateCounts = { E: 0, P: 0, R: 0, NL: 0, A: 0 };

        html += `
            <div style="text-transform: capitalize; font-size: 12px; color: #555; margin-top: 15px; margin-bottom: 5px;">
                ${cat}
            </div>

            <table border="1" width="100%" cellspacing="0" cellpadding="4" style="border-collapse:collapse; font-size:10px; text-align:center;">
                <thead>
                    <tr style="background:#f1f1f1;">
                        <th>Désignation</th>
                        <th>Financement</th>
                        <th>Montant alloué</th>
                        <th>Montants des engagements cumulés</th>
                        <th>Montant des paiements cumulés</th>
                        <th>Delai Etudes</th>
                        <th>Delai Réalisation</th>
                        <th>ODS Etudes</th>
                        <th>ODS Réalisation</th>
                        <th>E</th>
                        <th>P</th>
                        <th>R</th>
                        <th>NL</th>
                        <th>A</th>
                        <th>Taux d'avancement</th>
                        <th>Date réception</th>
                        <th>Observations / Contraintes</th>
                    </tr>
                </thead>
                <tbody>
        `;

        if (filtered.length > 0) {
            filtered.forEach(p => {
                let alloue = Number(p.montantAlloue ?? 0);
                let engage = Number(p.montantEC ?? 0);
                let paye   = Number(p.montantPC ?? 0);

                totalAlloue += alloue;
                totalEngage += engage;
                totalPaye   += paye;

                if (p.etatPhysique) {
                    stateCounts[p.etatPhysique] = (stateCounts[p.etatPhysique] || 0) + 1;
                    globalStates[p.etatPhysique] = (globalStates[p.etatPhysique] || 0) + 1;
                }

                html += `
                    <tr>
                        <td style="color: blue; text-decoration: underline;">${p.designation}</td>
                        <td>${p.finance ?? '-'}</td>
                        <td>${alloue.toFixed(2)}</td>
                        <td>${engage.toFixed(2)}</td>
                        <td>${paye.toFixed(2)}</td>
                        <td>${p.delaiEtudes ?? '-'}</td>
                        <td>${p.delaiRealisation ?? '-'}</td>
                        <td>${p.odsEtudes ?? '-'}</td>
                        <td>${p.odsRealisation ?? '-'}</td>
                        <td>${p.etatPhysique === 'E' ? '1' : ''}</td>
                        <td>${p.etatPhysique === 'P' ? '1' : ''}</td>
                        <td>${p.etatPhysique === 'R' ? '1' : ''}</td>
                        <td>${p.etatPhysique === 'NL' ? '1' : ''}</td>
                        <td>${p.etatPhysique === 'A' ? '1' : ''}</td>
                        <td>${p.tauxA ?? 0}%</td>
                        <td>${p.dateReception ?? '-'}</td>
                        <td>${p.observations ?? '-'}</td>
                    </tr>
                `;
            });
        } else {
            html += `
                <tr>
                    <td colspan="17" style="text-align:center; color:#888;">Aucun projet</td>
                </tr>
            `;
        }

        html += `
                <tr style="font-weight:bold; background:#eee;">
                    <td colspan="2" style="text-align:left;">Total ${cat}</td>
                    <td>${totalAlloue.toFixed(2)}</td>
                    <td>${totalEngage.toFixed(2)}</td>
                    <td>${totalPaye.toFixed(2)}</td>
                    <td colspan="12"></td>
                </tr>
                </tbody>
            </table>
        `;

        globalAlloue += totalAlloue;
        globalEngage += totalEngage;
        globalPaye   += totalPaye;
    });

    html += `
        <div style="margin-top:20px; font-weight:bold;">
            Total Général: Alloué ${globalAlloue.toFixed(2)}, Engagé ${globalEngage.toFixed(2)}, Payé ${globalPaye.toFixed(2)}
        </div>
    `;

    document.body.innerHTML = html;
    window.print();
    document.body.innerHTML = original;
    location.reload();
}
</script>


<script>
document.getElementById('select-recap')?.addEventListener('change', function () {
    const recap = this.value;
    const year  = document.getElementById('year').value;
    const wilaya = document.getElementById('wilaya')?.value ?? '{{ auth()->id() }}';

    if (recap === 'DUPW') {
        window.location.href = `/projet/gestionprojets/${wilaya}/tout/${year}`;
    } else {
        window.location.href = `/projet/recaps/${recap}/tout/${year}`;
    }
});
</script>
<div id="print-canvas" style="display:none; padding:30px; font-family:Arial;">
    
    <div style="margin-bottom:30px;">
        <img src="{{ asset('img/logo-head.png') }}" width="220">
    </div>

    <h3 style="text-align:center; margin-bottom:30px;">
        Liste des projets de l'UPW {{ auth()->user()->name }}  
        de l'année {{ $year }}
    </h3>

    <table border="1" width="100%" cellspacing="0" cellpadding="5"
           style="border-collapse:collapse; font-size:12px; text-align:center;">

        <thead>
            <tr style="background:#f2f2f2;">
                <th>Désignation</th>
                <th>Financement</th>
                <th>Montant alloué</th>
                <th>Montants engagés cumulés</th>
                <th>Montants paiements cumulés</th>
                <th>ODS</th>
                <th>État physique</th>
                <th>Taux</th>
                <th>Date réception</th>
                <th>Observations</th>
            </tr>
        </thead>

        <tbody id="print-body">
        </tbody>

        <tfoot id="print-footer">
        </tfoot>

    </table>
</div>
@endsection