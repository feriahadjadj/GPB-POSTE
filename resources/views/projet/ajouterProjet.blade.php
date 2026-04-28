@extends('layouts.poste')

@section('content')
<div class="container py-5">
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-5">
       
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
    <h1 class="project-title">
       Projet
    </h1>
</div>
        <div class="row g-4 justify-content-center mt-4">
            <div class="col-md-4">
                <div class="card project-card" onclick="showPhase(1)" id="card-1">
                    <div class="card-body text-center d-flex flex-column align-items-center justify-content-between h-100">
                        <div id="lock-1" class="lock-overlay"></div>
                        <div class="card-icon bg-blue shadow-blue">
   <i class="fas fa-clipboard-list"></i>
</div>
                        <h3 class="card-title"> Phase: Identification</h3>
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
                        <h3 class="card-title"> Phase: Étude</h3>
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
                        <h3 class="card-title"> Phase: Réalisation</h3>
                        <div class="card-badge tag-red" id="badge-3">
                            En attente
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="text-center mt-5">
            <button type="button" id="btnSubmitFinal" class="btn btn-submit-final d-none" onclick="submitProject()">
               <i class="fas fa-cloud-upload-alt me-2"></i> Enregistrer le Projet Complet
            </button>
        </div> -->
    </div>

    {{-- FORMULAIRES --}}
    <form id="projectForm" method="POST">
        @csrf
        <input type="hidden" name="project_id" id="project_id" value="{{ $projet->id ?? '' }}">
        <input type="hidden" name="saved_step" id="saved_step" value="{{ $projet->step ?? 1 }}">
        
       <div class="phase" id="phase1">
    <div class="form-container">
        <div class="form-header">
            <div>
                <h3 class="fw-bold mb-1"> Phase Identification</h3>
                <p class="text-muted mb-0 small">Détails de base et suivi financier initial</p>
            </div>
            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" onclick="backToMenu()">
                Menu
            </button>
        </div>
        <div class="form-body p-4 p-md-5">
            <div class="modern-grid">
                {{-- Ligne 1 : Nom du Projet --}}
                <div class="field full">
                    <label>Désignation du Projet *</label>
                    <input type="text" name="designation" id="designation"
                           value="{{ old('designation', $projet->designation ?? '') }}"
                           placeholder="Nom du projet..." required>
                </div>

                {{-- Ligne 2 : Nature et Financement --}}
                <div class="field">
                    <label>Nature du Projet *</label>
                    <select name="nature" required>
                        @foreach($natures as $n)
                            <option value="{{ $n->name }}"
                                {{ old('nature', $projet->nature ?? '') == $n->name ? 'selected' : '' }}>
                                {{ $n->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label>Source de Financement</label>
                    <select name="finance">
                        @foreach($finances as $f)
                            <option value="{{ $f->name }}"
                                {{ old('finance', $projet->finance ?? '') == $f->name ? 'selected' : '' }}>
                                {{ $f->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Ligne 3 : SECTION FINANCIÈRE (Les 3 montants) --}}
<div class="field">
    <label>Montant Alloué (DA)</label>
    <input type="number" name="montantAlloue" step="0.01"
           value="{{ old('montantAlloue', $projet->montantAlloue ?? '0.00') }}">
</div>

<div class="field">
    <label>Montant Engagé (DA)</label>
    <input type="number" name="montantEC" step="0.01"
           value="{{ old('montantEC', $projet->montantEC ?? '0.00') }}"
           placeholder="0.00">
</div>

<div class="field">
    <label>Montant Payé (DA)</label>
    <input type="number" name="montantPC" step="0.01"
           value="{{ old('montantPC', $projet->montantPC ?? '0.00') }}"
           placeholder="0.00">
</div>
                {{-- Ligne 4 : Date --}}
                <div class="field">
                    <label>année d’inscription</label>
                    <input type="date" name="date_creation"
                           value="{{ old('date_creation', !empty($projet->date_creation) ? \Carbon\Carbon::parse($projet->date_creation)->format('Y-m-d') : date('Y-m-d')) }}">
                </div>
            </div>
        </div>
        <div class="form-footer d-flex justify-content-end">
            <button type="button" class="btn-action shadow-sm" onclick="savePhase(1)">
                Confirmer  <i class="fas fa-arrow-right ms-2"></i>
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
                            <input type="date" name="odsEtude"
                                    value="{{ old('odsEtude', !empty($projet->odsEtude) ? \Carbon\Carbon::parse($projet->odsEtude)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="field">
                            <label>Délai Études</label>
                            <div class="input-duo">
                                @php
                                    $delaiEValue = 0;
                                    $delaiEType = 'j';

                                    if (!empty($projet->delaiE)) {
                                        $parts = explode(' ', trim($projet->delaiE));
                                        $delaiEValue = $parts[0] ?? 0;
                                        $delaiEType = $parts[1] ?? 'j';
                                    }
                                @endphp
                                <input type="number" name="delaiE_value" value="{{ old('delaiE_value', $delaiEValue) }}">
                                <select name="delaiE_type">
                                    <option value="j" {{ old('delaiE_type', $delaiEType) == 'j' ? 'selected' : '' }}>Jours</option>
                                    <option value="m" {{ old('delaiE_type', $delaiEType) == 'm' ? 'selected' : '' }}>Mois</option>
                                </select>
                            </div>
                        </div>
                        <div class="field">
                            <label>Lancement Appel d'offre / Consultation</label>
                            <input type="date" name="date_lancement"
                                    value="{{ old('date_lancement', !empty($projet->date_lancement) ? \Carbon\Carbon::parse($projet->date_lancement)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="field">
                            <label>Ouverture des plis</label>
                            <input type="date" name="date_ouverture_plis"
                                    value="{{ old('date_ouverture_plis', !empty($projet->date_ouverture_plis) ? \Carbon\Carbon::parse($projet->date_ouverture_plis)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="field">
                            <label>Attribution provisoire</label>
                            <input type="date" name="date_attribution"
                                    value="{{ old('date_attribution', !empty($projet->date_attribution) ? \Carbon\Carbon::parse($projet->date_attribution)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="field">
                            <label>Validation Commission</label>
                            <input type="date" name="date_validation_commission"
                                    value="{{ old('date_validation_commission', !empty($projet->date_validation_commission) ? \Carbon\Carbon::parse($projet->date_validation_commission)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="field full">
                            <label>Observations Étude</label>
                            <textarea name="observation_etude" rows="2" placeholder="Notes sur la phase administrative...">{{ old('observation_etude', $projet->observation_etude ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="form-footer d-flex justify-content-end">
                    <button type="button" class="btn-action shadow-sm" onclick="savePhase(2)">
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

                <!-- ODS Réalisation -->
                <div class="field">
                    <label><i class="fas fa-map-marker-alt me-2"></i>ODS Réalisation</label>
                    <input type="date" name="odsRealisation"
                        value="{{ old('odsRealisation', !empty($projet->odsRealisation) ? \Carbon\Carbon::parse($projet->odsRealisation)->format('Y-m-d') : '') }}">
                </div>
 <div class="field">
                            <label>Délai REALISATION</label>
                            <div class="input-duo">
                                @php
                                    $delaiRValue = 0;
                                    $delaiRType = 'j';

                                    if (!empty($projet->delaiR)) {
                                        $parts = explode(' ', trim($projet->delaiR));
                                        $delaiRValue = $parts[0] ?? 0;
                                        $delaiRType = $parts[1] ?? 'j';
                                    }
                                @endphp
                                <input type="number" name="delaiR_value" value="{{ old('delaiR_value', $delaiRValue) }}">
                                <select name="delaiR_type">
                                    <option value="j" {{ old('delaiR_type', $delaiRType) == 'j' ? 'selected' : '' }}>Jours</option>
                                    <option value="m" {{ old('delaiR_type', $delaiRType) == 'm' ? 'selected' : '' }}>Mois</option>
                                </select>
                            </div>
                        </div>
                <!-- État physique -->
                <div class="field">
                    <label><i class="fas fa-chart-line me-2"></i>État Physique</label>
                    <select name="etatPhysique" id="etatPhysique">
                        <option value="R" {{ old('etatPhysique', $projet->etatPhysique ?? 'R') == 'R' ? 'selected' : '' }}>En Cours</option>
                        <option value="A" {{ old('etatPhysique', $projet->etatPhysique ?? '') == 'A' ? 'selected' : '' }}>Achevé</option>
                    </select>
                </div>

                <!-- 🔥 NEW FIELDS -->
                <div class="field">
                    <label>Lancement Appel d'offre / Consultation</label>
                    <input type="date" name="date_lancement"
                        value="{{ old('date_lancement', !empty($projet->date_lancement) ? \Carbon\Carbon::parse($projet->date_lancement)->format('Y-m-d') : '') }}">
                </div>

                <div class="field">
                    <label>Ouverture des plis</label>
                    <input type="date" name="date_ouverture_plis"
                        value="{{ old('date_ouverture_plis', !empty($projet->date_ouverture_plis) ? \Carbon\Carbon::parse($projet->date_ouverture_plis)->format('Y-m-d') : '') }}">
                </div>

                <div class="field">
                    <label>Attribution provisoire</label>
                    <input type="date" name="date_attribution"
                        value="{{ old('date_attribution', !empty($projet->date_attribution) ? \Carbon\Carbon::parse($projet->date_attribution)->format('Y-m-d') : '') }}">
                </div>

                <div class="field">
                    <label>Validation Commission</label>
                    <input type="date" name="date_validation_commission"
                        value="{{ old('date_validation_commission', !empty($projet->date_validation_commission) ? \Carbon\Carbon::parse($projet->date_validation_commission)->format('Y-m-d') : '') }}">
                </div>

                <!-- Taux -->
                <div class="field full">
                    <label class="d-flex justify-content-between">
                        <span><i class="fas fa-tasks me-2"></i>Taux d'Avancement (%)</span>
                        <div class="d-flex align-items-center bg-light px-2 rounded-3 border">
                            <input type="number" id="inputTaux" class="taux-input" min="0" max="100"
                                value="{{ old('tauxA', $projet->tauxA ?? 0) }}">
                            <span class="fw-bold" style="color: #4D95FE;">%</span>
                        </div>
                    </label>

                    <input type="range" name="tauxA" id="rangeTaux"
                        class="form-range custom-range" min="0" max="100"
                        value="{{ old('tauxA', $projet->tauxA ?? 0) }}">
                </div>

                <!-- Observations -->
                <div class="field full">
                    <label><i class="fas fa-comment-dots me-2"></i>Observations Générales</label>
                    <textarea name="observation" rows="3">
                        {{ old('observation', $projet->observation ?? '') }}
                    </textarea>
                </div>

              

            </div>
        </div>

        <div class="form-footer d-flex justify-content-end">
            <button type="button" class="btn-action shadow-sm" onclick="savePhase(3)">
                Confirmer <i class="fas fa-save ms-2"></i>
            </button>
        </div>
    </div>
</div>

<style>
:root {
    --blue-main: #4D95FE;
    --yellow-shadow: #FDC90A;
    --dark: #2d3436;
    --bg-light: #fcfdfe;
}

body {
    background-color: var(--bg-light);
    font-family: 'Inter', sans-serif;
}

/* FORM CONTAINER */
.form-container { 
    background: white; 
    border-radius: 30px; 
  
    overflow: hidden; 
    border: 2px solid rgba(253, 201, 10, 0.2);
}

.form-header {
    padding: 30px 40px;
    border-bottom: 1px solid #f1f3f5;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.form-footer {
    padding: 20px 40px 30px;
}

/* BUTTONS */
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

/* ========================= */
/* 🔥 CARD DESIGN + ANIMATION */
/* ========================= */

.project-card {
    border: none !important;
    border-radius: 35px !important;
    height: 400px;
    padding: 40px 20px;
    background: #ffffff;
    cursor: pointer;
    position: relative;
    overflow: hidden;

    transition: all 0.35s ease;
    
}


/* animated shine layer */
.project-card::before {
    content: "";
    position: absolute;
    top: -100%;
    left: -100%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
        120deg,
        transparent,
        rgba(77, 149, 254, 0.15),
        transparent
    );
    transform: rotate(25deg);
    transition: 0.6s;
}

/* hover animation */
.project-card:hover:not(.card-locked) {
    transform: translateY(-12px) scale(1.02);
    box-shadow: 0 30px 60px rgba(77, 149, 254, 0.2);
}

/* shine movement */
.project-card:hover::before {
    top: 100%;
    left: 100%;
}

/* locked card */
.card-locked {
    filter: grayscale(1);
    opacity: 0.6;
    cursor: not-allowed !important;
}

/* lock icon */
.lock-overlay {
    position: absolute;
    top: 30px;
    right: 30px;
    font-size: 1.4rem;
    color: #adb5bd;
}

/* ========================= */
/* 🎯 ICON CLEAN STYLE */
/* ========================= */

.card-icon {
    width: 110px;
    height: 110px;
    border-radius: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.8rem;

    background: transparent !important;   /* removed background */
    color: var(--blue-main);              /* keep blue icon */
    box-shadow: none !important;

    transition: all 0.3s ease;
    
}

/* icon animation */
.project-card:hover .card-icon {
    transform: scale(1.15) rotate(5deg);
}

/* TEXT */
.card-title {
    font-weight: 800;
    font-size: 1.6rem;
    color: var(--dark);
    margin: 20px 0;
}
.project-title {
    font-weight: 900;
    font-size: 2.6rem;
    letter-spacing: -1px;
    color: #2D3436;
    position: relative;
    display: inline-block;
}

/* subtle underline animation */
.project-title::after {
    content: "";
    display: block;
    width: 60%;
    height: 4px;
    margin: 10px auto 0;
    border-radius: 10px;
    background: linear-gradient(90deg, #FDC90A	  );
    transition: 0.4s ease;
}

/* hover effect */
.project-title:hover::after {
    width: 100%;
}

/* optional soft glow */
.project-title:hover {
   
    transition: 0.3s;
}
/* BADGES */
.card-badge {
    padding: 8px 20px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.85rem;
}
/* Default (optional fallback) */
.card-icon i {
    color: #4D95FE;
}

/* Per card colors */
#card-1 .card-icon i { color:  #0068FE	 !important; }  /* blue */
#card-2 .card-icon i { color:  #0068FE	!important; }  /* purple */
#card-3 .card-icon i { color:  #0068FE	!important; }  /* green */
.tag-blue {
    background: rgba(77,149,254,0.1);
    color: var(--blue-main);
}

.tag-red {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    border: 1px solid rgba(220, 53, 69, 0.2);
}

.tag-gray {
    background: #f1f3f5;
    color: #6c757d;
}

/* GRID */
.modern-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.field.full {
    grid-column: span 2;
}

/* INPUTS */
.field label {
    font-size: 0.75rem;
    font-weight: 800;
    color: #495057;
    margin-bottom: 8px;
    display: block;
    text-transform: uppercase;
}

.field input,
.field select,
.field textarea {
    border: 2px solid #edf2f7;
    border-radius: 15px;
    padding: 14px;
    background: #f8fafc;
    width: 100%;
    transition: 0.3s;
}

.field input:focus {
    border-color: var(--blue-main);
    outline: none;
    background: white;
}

/* RANGE */
.taux-input {
    border: none !important;
    background: transparent !important;
    width: 45px !important;
    padding: 0 !important;
    font-weight: bold;
    color: var(--blue-main);
    text-align: right;
}

.custom-range::-webkit-slider-thumb {
    background: var(--blue-main);
}

/* PHASE ANIMATION */
.phase {
    display: none;
}

.active-phase {
    display: block;
    animation: fadeInUp 0.5s ease forwards;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
<script>
    window.phase1Done = !!document.getElementById('project_id').value;

    window.showPhase = function (n) {
        document.querySelectorAll('.phase').forEach(function (p) {
            p.classList.remove('active-phase');
        });

        var phase = document.getElementById('phase' + n);
        if (phase) {
            phase.classList.add('active-phase');
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    window.backToMenu = function () {
        document.querySelectorAll('.phase').forEach(function (p) {
            p.classList.remove('active-phase');
        });

        var menu = document.getElementById('phase-selection');
        if (menu) {
            menu.classList.add('active-phase');
        }
    };

    window.checkAccess = function (n) {
        if (!window.phase1Done) {
            if (window.Swal) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Accès restreint',
                    text: 'Veuillez valider l\'Identification en premier.',
                    confirmButtonColor: '#4D95FE'
                });
            } else {
                alert('Veuillez valider l\'Identification en premier.');
            }
            return;
        }

        window.showPhase(n);
    };

    function markBadgeDone(badgeId) {
        const badge = document.getElementById(badgeId);
        badge.innerHTML = '<i class="fas fa-check-circle"></i> Terminé';
        badge.className = 'card-badge bg-light text-success border';
    }

    function markBadgeWaiting(badgeId) {
        const badge = document.getElementById(badgeId);
        badge.innerHTML = 'En attente';
        badge.className = 'card-badge tag-red';
    }

    function markBadgeAvailable(badgeId) {
        const badge = document.getElementById(badgeId);
        badge.innerHTML = 'Disponible';
        badge.className = 'card-badge tag-gray';
    }

    function initializeCardsUI() {
        const hasProject = !!document.getElementById('project_id').value;
        const savedStep = parseInt(document.getElementById('saved_step').value || '1', 10);

        if (hasProject) {
            window.phase1Done = true;

            document.getElementById('card-2').classList.remove('card-locked');
            document.getElementById('card-3').classList.remove('card-locked');
            document.getElementById('lock-2').innerHTML = '<i class="fas fa-unlock-alt text-success"></i>';
            document.getElementById('lock-3').innerHTML = '<i class="fas fa-unlock-alt text-success"></i>';

            markBadgeDone('badge-1');

            if (savedStep >= 2) {
                markBadgeDone('badge-2');
            } else {
                markBadgeAvailable('badge-2');
            }

            if (savedStep >= 3) {
                markBadgeDone('badge-3');
            } else {
                markBadgeAvailable('badge-3');
            }
        } else {
            window.phase1Done = false;
            markBadgeWaiting('badge-2');
            markBadgeWaiting('badge-3');
        }
    }

    window.savePhase = async function (step) {
        console.log('savePhase called, step =', step);

        if (step === 1) {
            var desig = document.getElementById('designation').value;
            if (!desig || !desig.trim()) {
                if (window.Swal) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Champ requis',
                        text: 'La désignation est obligatoire !',
                        confirmButtonColor: '#4D95FE'
                    });
                } else {
                    alert('La désignation est obligatoire !');
                }
                return;
            }
        }

        var form = document.getElementById('projectForm');
        var formData = new FormData(form);
        formData.append('step', step);

        try {
            var response = await fetch("{{ route('projet.saveStep') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            console.log('response status =', response.status);

            let result;
            const rawText = await response.text();
            console.log('RAW RESPONSE:', rawText);

            try {
                result = JSON.parse(rawText);
            } catch (jsonError) {
                console.error('JSON parse error:', jsonError);
                console.error('Server raw response:', rawText);
                throw new Error('La réponse du serveur n’est pas un JSON valide.');
            }

            console.log('result =', result);

            if (!response.ok || !result.success) {
                if (window.Swal) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: result.message || 'Une erreur est survenue.',
                        confirmButtonColor: '#4D95FE'
                    });
                } else {
                    alert(result.message || 'Une erreur est survenue.');
                }
                return;
            }

            if (result.project_id) {
                document.getElementById('project_id').value = result.project_id;
            }
            document.getElementById('saved_step').value = step;

            initializeCardsUI();

            if (window.Swal) {
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: result.message,
                    timer: 1400,
                    showConfirmButton: false
                });
            }

            window.backToMenu();

        } catch (error) {
            console.error('savePhase error:', error);

            if (window.Swal) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: error.message || 'Impossible d’enregistrer le projet.',
                    confirmButtonColor: '#4D95FE'
                });
            } else {
                alert(error.message || 'Impossible d’enregistrer le projet.');
            }
        }
    };

    /* window.submitProject = function () {
        document.getElementById('projectForm').submit();
    };*/

    document.addEventListener('DOMContentLoaded', function () {
        var range = document.getElementById('rangeTaux');
        var input = document.getElementById('inputTaux');
        var etatPhysique = document.getElementById('etatPhysique');

        if (range && input) {
            range.addEventListener('input', function (e) {
                input.value = e.target.value;
            });

            input.addEventListener('input', function (e) {
                var val = parseInt(e.target.value, 10) || 0;
                if (val > 100) val = 100;
                if (val < 0) val = 0;
                range.value = val;
            });
        }

        if (etatPhysique && range && input) {
            etatPhysique.addEventListener('change', function () {
                if (this.value === 'A') {
                    range.value = 100;
                    input.value = 100;
                }
            });
        }

        initializeCardsUI();
    });
</script>
<script>
    let oldValue = {{ $projet->tauxA ?? 0 }};

    const range = document.getElementById('rangeTaux');
    const input = document.getElementById('inputTaux');

    // sync range -> input
    range.addEventListener('input', function () {
        if (this.value < oldValue) {
            this.value = oldValue; // يرجع للقيمة القديمة
        }
        input.value = this.value;
    });

    // sync input -> range
    input.addEventListener('input', function () {
        if (this.value < oldValue) {
            this.value = oldValue;
        }
        range.value = this.value;
    });
</script>
@endsection