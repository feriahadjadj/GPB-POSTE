@extends('layouts.poste')

@section('content')
<div class="container py-5">
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-5">
        <a href="{{ url()->previous() }}" class="btn btn-light shadow-sm btn-sm rounded-pill px-4 hover-lift">
            <i class="fas fa-chevron-left me-2 text-primary"></i> Quitter l'édition
        </a>
        <div id="status-container">
            <span class="badge bg-white text-dark shadow-sm py-2 px-4 rounded-pill border-0" style="border-left: 4px solid #FDC90A !important;">
                <i class="fas fa-tasks me-2" style="color: #4D95FE;"></i> 
                Statut: <span id="status-text" class="fw-bold text-muted">Initialisation</span>
            </span>
        </div>
    </div>

    {{-- DASHBOARD : MENU PAR CARTES --}}
    <div class="phase active-phase" id="phase-selection">
        <div class="text-center mb-5">
            <h1 class="fw-black display-5 mb-2" style="color: #2D3436; letter-spacing: -1px;">
                Nouveau Projet
            </h1>
        </div>

        <div class="row g-4 justify-content-center mt-4">
            <div class="col-md-4">
                <div class="card project-card" onclick="showPhase(1)" id="card-1">
                    <div class="card-body text-center d-flex flex-column align-items-center justify-content-between h-100">
                        <div id="lock-1" class="lock-overlay"></div>
                        <div class="card-icon bg-blue shadow-blue">
                            <i class="fas fa-fingerprint"></i>
                        </div>
                        <h3 class="card-title">Identification</h3>
                        <div class="card-badge tag-blue" id="badge-1">
                            <i class="fas fa-star me-1"></i> Obligatoire
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card project-card card-locked" onclick="checkAccess(2)" id="card-2">
                    <div class="card-body text-center d-flex flex-column align-items-center justify-content-between h-100">
                        <div class="lock-overlay" id="lock-2">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="card-icon shadow-blue" style="background: #4D95FE; width: 110px; height: 110px; border-radius: 30px; display: flex; align-items: center; justify-content: center; font-size: 2.8rem; color: white;">
                            <i class="fas fa-pen-ruler"></i>
                        </div>
                        <h3 class="card-title">Phase Étude</h3>
                        <div class="card-badge tag-red" id="badge-2">
                            En attente
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card project-card card-locked" onclick="checkAccess(3)" id="card-3">
                    <div class="card-body text-center d-flex flex-column align-items-center justify-content-between h-100">
                        <div class="lock-overlay" id="lock-3">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="card-icon shadow-blue" style="background: #4D95FE; width: 110px; height: 110px; border-radius: 30px; display: flex; align-items: center; justify-content: center; font-size: 2.8rem; color: white;">
                            <i class="fas fa-helmet-safety"></i>
                        </div>
                        <h3 class="card-title">Réalisation</h3>
                        <div class="card-badge tag-red" id="badge-3">
                            En attente
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <button type="button" id="btnSubmitFinal" class="btn btn-submit-final d-none" onclick="submitProject()">
                <i class="fas fa-cloud-upload-alt me-2"></i> Enregistrer le Projet Complet
            </button>
        </div>
    </div>

    {{-- FORMULAIRES --}}
    <form id="projectForm" action="{{ route('projet.store') }}" method="POST">
        @csrf
        
        <div class="phase" id="phase1">
            <div class="form-container">
                <div class="form-header">
                    <div>
                        <h3 class="fw-bold mb-1">Identification</h3>
                        <p class="text-muted mb-0 small">Détails de base et financement</p>
                    </div>
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" onclick="backToMenu()">
                        Menu
                    </button>
                </div>
                <div class="form-body p-4 p-md-5">
                    <div class="modern-grid">
                        <div class="field full">
                            <label>Désignation du Projet *</label>
                            <input type="text" name="designation" id="designation" placeholder="Nom du projet..." required>
                        </div>
                        <div class="field">
                            <label>Nature du Projet *</label>
                            <select name="nature" required>
                                @foreach($natures as $n) <option value="{{$n->name}}">{{$n->name}}</option> @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>Source de Financement</label>
                            <select name="finance">
                                @foreach($finances as $f) <option value="{{$f->name}}">{{$f->name}}</option> @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <label>Montant Alloué (DA)</label>
                            <input type="number" name="montantAlloue" step="0.01" value="0.00">
                        </div>
                        <div class="field">
                            <label>Date de Création</label>
                            <input type="date" name="date_creation" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>
                <div class="form-footer d-flex justify-content-end">
                    <button type="button" class="btn-action shadow-sm" onclick="unlockPhases()">
                        Confirmer <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="phase" id="phase2">
            <div class="form-container">
                <div class="form-header">
                    <div>
                        <h3 class="fw-bold mb-1">Phase Étude & Administrative</h3>
                        <p class="text-muted mb-0 small">Suivi des appels d'offres et attributions</p>
                    </div>
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" onclick="backToMenu()">
                        Menu
                    </button>
                </div>
                <div class="form-body p-4 p-md-5">
                    <div class="modern-grid">
                        <div class="field">
                            <label>ODS Études (Date)</label>
                            <input type="date" name="odsEtude">
                        </div>
                        <div class="field">
                            <label>Délai Études</label>
                            <div class="input-duo">
                                <input type="number" name="delaiE_value" value="0">
                                <select name="delaiE_type">
                                    <option value="j">Jours</option>
                                    <option value="m">Mois</option>
                                </select>
                            </div>
                        </div>
                        <div class="field">
                            <label>Lancement Appel d'offre / Consultation</label>
                            <input type="date" name="date_lancement">
                        </div>
                        <div class="field">
                            <label>Ouverture des plis</label>
                            <input type="date" name="date_ouverture_plis">
                        </div>
                        <div class="field">
                            <label>Attribution provisoire</label>
                            <input type="date" name="date_attribution">
                        </div>
                        <div class="field">
                            <label>Validation Commission</label>
                            <input type="date" name="date_validation_commission">
                        </div>
                        <div class="field full">
                            <label>Observations Étude</label>
                            <textarea name="observation_etude" rows="2" placeholder="Notes sur la phase administrative..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-footer d-flex justify-content-end">
                    <button type="button" class="btn-action shadow-sm" onclick="backToMenu()">
                        Confirmer <i class="fas fa-check ms-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="phase" id="phase3">
            <div class="form-container">
                <div class="form-header">
                    <div>
                        <h3 class="fw-bold mb-1">Réalisation Terrain</h3>
                        <p class="text-muted mb-0 small">Suivi physique des travaux</p>
                    </div>
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" onclick="backToMenu()">
                        Menu
                    </button>
                </div>
                <div class="form-body p-4 p-md-5">
                    <div class="modern-grid">
                        <div class="field">
                            <label><i class="fas fa-map-marker-alt me-2"></i>ODS Réalisation</label>
                            <input type="date" name="odsRealisation">
                        </div>
                        <div class="field">
                            <label><i class="fas fa-chart-line me-2"></i>État Physique</label>
                            <select name="etatPhysique" id="etatPhysique">
                                <option value="R">En Cours</option>
                                <option value="A">Achevé</option>
                            </select>
                        </div>
                        <div class="field full">
                            <label class="d-flex justify-content-between">
                                <span><i class="fas fa-tasks me-2"></i>Taux d'Avancement (%)</span>
                                <div class="d-flex align-items-center bg-light px-2 rounded-3 border">
                                    <input type="number" id="inputTaux" class="taux-input" min="0" max="100" value="0">
                                    <span class="fw-bold" style="color: #4D95FE;">%</span>
                                </div>
                            </label>
                            <input type="range" name="tauxA" id="rangeTaux" class="form-range custom-range" min="0" max="100" value="0">
                        </div>
                        <div class="field full">
                            <label><i class="fas fa-comment-dots me-2"></i>Observations Générales</label>
                            <textarea name="observation" rows="3" placeholder="Rapport de chantier..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-footer d-flex justify-content-end">
                    <button type="button" class="btn-action shadow-sm" onclick="backToMenu()">
                       Confirmer <i class="fas fa-save ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    :root {
        --blue-main: #4D95FE;
        --yellow-shadow: #FDC90A;
        --dark: #2d3436;
        --bg-light: #fcfdfe;
    }

    body { background-color: var(--bg-light); font-family: 'Inter', sans-serif; }

    /* FORM CONTAINER WITH YELLOW SHADOW */
    .form-container { 
        background: white; 
        border-radius: 30px; 
        box-shadow: 0 15px 45px rgba(253, 201, 10, 0.4); /* #FDC90A Intense Shadow */
        overflow: hidden; 
        border: 2px solid rgba(253, 201, 10, 0.2);
    }

    .form-header { padding: 30px 40px; border-bottom: 1px solid #f1f3f5; display: flex; justify-content: space-between; align-items: center; }
    .form-footer { padding: 20px 40px 30px; }

    /* UNIFORM BLUE BUTTONS */
    .btn-action, .btn-submit-final {
        background-color: var(--blue-main) !important;
        color: white !important;
        border: none !important;
        padding: 12px 35px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-submit-final {
        padding: 18px 50px;
        border-radius: 50px;
        font-size: 1.1rem;
        box-shadow: 0 10px 20px rgba(77, 149, 254, 0.3);
    }

    .btn-action:hover, .btn-submit-final:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(77, 149, 254, 0.4);
        opacity: 0.95;
    }

    /* DASHBOARD CARDS */
    .project-card {
        border: none !important;
        border-radius: 35px !important;
        height: 400px;
        padding: 40px 20px;
        transition: all 0.4s ease;
        cursor: pointer;
        background: #ffffff;
        box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        position: relative;
    }
    .project-card:hover:not(.card-locked) {
        transform: translateY(-15px);
        box-shadow: 0 30px 60px rgba(77, 149, 254, 0.15);
    }
    .card-locked { filter: grayscale(1); opacity: 0.6; cursor: not-allowed !important; }
    .lock-overlay { position: absolute; top: 30px; right: 30px; font-size: 1.4rem; color: #adb5bd; }

    .card-icon {
        width: 110px;
        height: 110px;
        border-radius: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.8rem;
        color: white;
    }
    .bg-blue { background: var(--blue-main); }
    .shadow-blue { box-shadow: 0 12px 25px rgba(77, 149, 254, 0.3); }

    .card-title { font-weight: 800; font-size: 1.6rem; color: var(--dark); margin: 20px 0; }
    .card-badge { padding: 8px 20px; border-radius: 50px; font-weight: 600; font-size: 0.85rem; }
    
    .tag-blue { background: rgba(77,149,254,0.1); color: var(--blue-main); }
    .tag-red { background: rgba(220, 53, 69, 0.1); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.2); }
    .tag-gray { background: #f1f3f5; color: #6c757d; }

    /* INPUTS & GRID */
    .modern-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    .field.full { grid-column: span 2; }
    .field label { font-size: 0.75rem; font-weight: 800; color: #495057; margin-bottom: 8px; display: block; text-transform: uppercase; }
    .field input, .field select, .field textarea { border: 2px solid #edf2f7; border-radius: 15px; padding: 14px; background: #f8fafc; width: 100%; transition: 0.3s; }
    .field input:focus { border-color: var(--blue-main); outline: none; background: white; }

    /* RANGE & TAUX */
    .taux-input { border: none !important; background: transparent !important; width: 45px !important; padding: 0 !important; font-weight: bold; color: var(--blue-main); text-align: right; }
    .custom-range::-webkit-slider-thumb { background: var(--blue-main); }

    .phase { display: none; }
    .active-phase { display: block; animation: fadeInUp 0.5s ease forwards; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
    let phase1Done = false;

    function showPhase(n) {
        document.querySelectorAll('.phase').forEach(p => p.classList.remove('active-phase'));
        document.getElementById('phase' + n).classList.add('active-phase');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function backToMenu() {
        document.querySelectorAll('.phase').forEach(p => p.classList.remove('active-phase'));
        document.getElementById('phase-selection').classList.add('active-phase');
    }

    function checkAccess(n) {
        if (!phase1Done) {
            Swal.fire({ icon: 'warning', title: 'Accès restreint', text: 'Veuillez valider l\'Identification en premier.', confirmButtonColor: '#4D95FE' });
        } else {
            showPhase(n);
        }
    }

    function unlockPhases() {
        const desig = document.getElementById('designation').value;
        if (!desig.trim()) { 
            Swal.fire({ icon: 'error', title: 'Champ requis', text: 'La désignation est obligatoire !', confirmButtonColor: '#4D95FE' });
            return; 
        }
        
        phase1Done = true;
        document.getElementById('card-2').classList.remove('card-locked');
        document.getElementById('card-3').classList.remove('card-locked');
        document.getElementById('lock-2').innerHTML = '<i class="fas fa-unlock-alt text-success"></i>';
        document.getElementById('lock-3').innerHTML = '<i class="fas fa-unlock-alt text-success"></i>';
        document.getElementById('btnSubmitFinal').classList.remove('d-none');
        
        // Update badges from RED (Waiting) to GRAY (Available)
        document.getElementById('badge-1').innerHTML = '<i class="fas fa-check-circle"></i> Terminé';
        document.getElementById('badge-1').className = 'card-badge bg-light text-success border';
        
        document.getElementById('badge-2').className = 'card-badge tag-gray';
        document.getElementById('badge-3').className = 'card-badge tag-gray';
        
        backToMenu();
    }

    const range = document.getElementById('rangeTaux');
    const input = document.getElementById('inputTaux');

    range.addEventListener('input', (e) => { input.value = e.target.value; });
    input.addEventListener('input', (e) => {
        let val = parseInt(e.target.value) || 0;
        if (val > 100) val = 100;
        if (val < 0) val = 0;
        range.value = val;
    });

    document.getElementById('etatPhysique').addEventListener('change', function() {
        if (this.value === 'A') {
            range.value = 100;
            input.value = 100;
        }
    });

    function submitProject() { document.getElementById('projectForm').submit(); }
</script>
@endsection