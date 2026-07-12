@extends('layouts.app')

@section('title', 'Styleguide')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Styleguide</h1>
        <p class="page-subtitle">Référence des composants GestApp University 3.0</p>
    </div>
</div>

{{-- ====== COULEURS ====== --}}
<x-card title="Palette de couleurs" icon="bi-palette" class="mb-6">
    <div class="d-flex flex-wrap gap-3">
        @foreach([
            ['label'=>'Primary','bg'=>'var(--clr-primary)'],
            ['label'=>'Primary Light','bg'=>'var(--clr-primary-light)','dark'=>true],
            ['label'=>'Success','bg'=>'var(--clr-success)'],
            ['label'=>'Warning','bg'=>'var(--clr-warning)'],
            ['label'=>'Danger','bg'=>'var(--clr-danger)'],
            ['label'=>'Info','bg'=>'var(--clr-info)'],
            ['label'=>'Gray 100','bg'=>'var(--clr-gray-100)','dark'=>true],
            ['label'=>'Gray 400','bg'=>'var(--clr-gray-400)'],
            ['label'=>'Gray 800','bg'=>'var(--clr-gray-800)'],
        ] as $c)
        <div style="width:100px;">
            <div style="background:{{ $c['bg'] }};height:60px;border-radius:var(--border-radius);border:1px solid var(--border-color);"></div>
            <div class="text-xs mt-1 text-muted">{{ $c['label'] }}</div>
        </div>
        @endforeach
    </div>
</x-card>

{{-- ====== TYPOGRAPHIE ====== --}}
<x-card title="Typographie" icon="bi-type" class="mb-6">
    <h1>Heading 1 — GestApp</h1>
    <h2>Heading 2 — GestApp</h2>
    <h3>Heading 3 — GestApp</h3>
    <h4>Heading 4 — GestApp</h4>
    <h5>Heading 5 — GestApp</h5>
    <h6>Heading 6 — GestApp</h6>
    <hr class="divider">
    <p>Paragraphe normal — Inter 400. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
    <p class="text-muted text-sm">Texte secondaire — sm/muted</p>
    <p class="text-xs text-muted">Texte xs — légende, meta-info</p>
    <strong>Gras fw-bold</strong> &nbsp; <span class="fw-semi">Semi-bold fw-semi</span> &nbsp; <span class="fw-medium">Medium fw-medium</span>
</x-card>

{{-- ====== BOUTONS ====== --}}
<x-card title="Boutons" icon="bi-cursor" class="mb-6">
    <div class="d-flex flex-wrap gap-3 mb-4">
        <button class="btn btn-primary"><i class="bi bi-plus-lg"></i> Primary</button>
        <button class="btn btn-secondary">Secondary</button>
        <button class="btn btn-outline-primary">Outline Primary</button>
        <button class="btn btn-success"><i class="bi bi-check-lg"></i> Success</button>
        <button class="btn btn-warning">Warning</button>
        <button class="btn btn-danger"><i class="bi bi-trash"></i> Danger</button>
        <button class="btn btn-ghost">Ghost</button>
    </div>
    <div class="d-flex flex-wrap gap-3 mb-4">
        <button class="btn btn-primary btn-sm">Small</button>
        <button class="btn btn-primary">Default</button>
        <button class="btn btn-primary btn-lg">Large</button>
    </div>
    <div class="d-flex flex-wrap gap-3">
        <button class="btn btn-primary btn-icon"><i class="bi bi-pencil"></i></button>
        <button class="btn btn-danger btn-icon btn-sm"><i class="bi bi-trash"></i></button>
        <button class="btn btn-ghost btn-icon"><i class="bi bi-three-dots-vertical"></i></button>
        <button class="btn btn-primary loading">Chargement</button>
        <button class="btn btn-primary" disabled>Désactivé</button>
    </div>
</x-card>

{{-- ====== BADGES ====== --}}
<x-card title="Badges" icon="bi-tag" class="mb-6">
    <div class="d-flex flex-wrap gap-3 mb-4">
        <span class="badge badge-primary">Primary</span>
        <span class="badge badge-success">Success</span>
        <span class="badge badge-warning">Warning</span>
        <span class="badge badge-danger">Danger</span>
        <span class="badge badge-info">Info</span>
        <span class="badge badge-secondary">Secondary</span>
        <span class="badge badge-dark">Dark</span>
    </div>
    <div class="d-flex flex-wrap gap-3 mb-4">
        <span class="badge badge-status badge-status--draft">Brouillon</span>
        <span class="badge badge-status badge-status--pending">En attente</span>
        <span class="badge badge-status badge-status--active">Active</span>
        <span class="badge badge-status badge-status--rejected">Rejeté</span>
        <span class="badge badge-status badge-status--done">Terminé</span>
    </div>
    <div class="d-flex flex-wrap gap-3">
        <span class="badge badge-solid-primary">Solid Primary</span>
        <span class="badge badge-solid-success">Solid Success</span>
        <span class="badge badge-solid-danger">Solid Danger</span>
        <span class="badge-count">5</span>
        <span class="badge-count">42</span>
    </div>
</x-card>

{{-- ====== ALERTES ====== --}}
<x-card title="Alertes" icon="bi-bell" class="mb-6">
    <x-alert type="success" :dismissible="false">Opération effectuée avec succès !</x-alert>
    <x-alert type="danger" :dismissible="false">Une erreur est survenue. Vérifiez les données.</x-alert>
    <x-alert type="warning" :dismissible="false">Attention — cette action est irréversible.</x-alert>
    <x-alert type="info" :dismissible="false">Information : les exports sont disponibles chaque lundi.</x-alert>
</x-card>

{{-- ====== KPI CARDS ====== --}}
<x-card title="KPI Cards" icon="bi-grid-1x2" class="mb-6">
    <div class="content-grid content-grid-4">
        @foreach([
            ['variant'=>'primary','icon'=>'bi-lightning-charge-fill','label'=>'Total activités','value'=>'248'],
            ['variant'=>'success','icon'=>'bi-check-circle-fill','label'=>'Actives','value'=>'184'],
            ['variant'=>'warning','icon'=>'bi-hourglass-split','label'=>'En attente','value'=>'64'],
            ['variant'=>'info','icon'=>'bi-cash-coin','label'=>'Budget','value'=>'12.4M'],
        ] as $kpi)
        <div class="card card-kpi card-kpi--{{ $kpi['variant'] }}">
            <div class="card-body d-flex align-items-center gap-4">
                <div class="kpi-icon"><i class="bi {{ $kpi['icon'] }}"></i></div>
                <div>
                    <div class="kpi-label">{{ $kpi['label'] }}</div>
                    <div class="kpi-value">{{ $kpi['value'] }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</x-card>

{{-- ====== TABLEAU ====== --}}
<x-card title="Table" icon="bi-table" :noPad="true" class="mb-6">
    <div class="table-wrapper" style="border:none;box-shadow:none;">
        <table class="table">
            <thead>
                <tr>
                    <th class="col-id">#</th>
                    <th class="sortable sort-asc">Label</th>
                    <th>Service</th>
                    <th>Statut</th>
                    <th class="col-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach([
                    ['id'=>1,'label'=>'Formation des enseignants','service'=>'DRH','status'=>1],
                    ['id'=>2,'label'=>'Audit financier Q3','service'=>'Finance','status'=>0],
                    ['id'=>3,'label'=>'Mise à jour du SI','service'=>'DSI','status'=>1],
                ] as $row)
                <tr>
                    <td class="col-id">{{ $row['id'] }}</td>
                    <td class="fw-medium">{{ $row['label'] }}</td>
                    <td><span class="badge badge-secondary">{{ $row['service'] }}</span></td>
                    <td>
                        @if($row['status'])
                            <span class="badge badge-status badge-status--active">Active</span>
                        @else
                            <span class="badge badge-status badge-status--pending">En attente</span>
                        @endif
                    </td>
                    <td class="col-actions">
                        <button class="btn btn-sm btn-ghost btn-icon"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-ghost btn-icon text-danger"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-card>

{{-- ====== FORMULAIRES ====== --}}
<x-card title="Formulaires" icon="bi-ui-checks" class="mb-6">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Label texte <span class="required">*</span></label>
                <input type="text" class="form-control" placeholder="Saisir une valeur…">
                <span class="form-text">Texte d'aide sous le champ.</span>
            </div>
            <div class="form-group">
                <label class="form-label">Valide</label>
                <input type="text" class="form-control is-valid" value="Valeur correcte">
                <span class="valid-feedback">Parfait !</span>
            </div>
            <div class="form-group">
                <label class="form-label">Invalide</label>
                <input type="text" class="form-control is-invalid" value="Mauvaise valeur">
                <span class="invalid-feedback">Ce champ est requis.</span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Sélect</label>
                <select class="form-select">
                    <option>Option 1</option>
                    <option>Option 2</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Textarea</label>
                <textarea class="form-control" rows="3" placeholder="Description…"></textarea>
            </div>
            <div class="search-input-wrapper form-group">
                <i class="bi bi-search"></i>
                <input type="search" class="form-control" placeholder="Rechercher…">
            </div>
        </div>
    </div>
</x-card>

{{-- ====== ÉTATS VIDES ====== --}}
<x-card title="État vide" icon="bi-inbox" class="mb-6">
    <x-empty-state
        icon="bi-lightning-charge"
        title="Aucune activité"
        message="Commencez par créer votre première activité."
    >
        <x-slot:action>
            <button class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Créer une activité</button>
        </x-slot:action>
    </x-empty-state>
</x-card>

{{-- ====== TOASTS (démo) ====== --}}
<x-card title="Toasts" icon="bi-bell" class="mb-6">
    <div class="d-flex gap-3 flex-wrap">
        <button class="btn btn-success btn-sm" onclick="Toast.success('Enregistrement effectué avec succès !')">
            <i class="bi bi-check-circle"></i> Succès
        </button>
        <button class="btn btn-danger btn-sm" onclick="Toast.error('Une erreur est survenue.')">
            <i class="bi bi-x-circle"></i> Erreur
        </button>
        <button class="btn btn-warning btn-sm" onclick="Toast.warning('Vérifiez vos données avant de continuer.')">
            <i class="bi bi-exclamation-triangle"></i> Warning
        </button>
        <button class="btn btn-outline-primary btn-sm" onclick="Toast.info('Nouvelle version disponible.')">
            <i class="bi bi-info-circle"></i> Info
        </button>
    </div>
</x-card>

@endsection
