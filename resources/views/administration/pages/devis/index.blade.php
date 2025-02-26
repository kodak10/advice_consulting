@extends('administration.layouts.master')

@section('content')
<div class="container-fluid">
           
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
      <div class="card-body px-4 py-3">
        <div class="row align-items-center">
          <div class="col-9">
            <h4 class="fw-semibold mb-8">Mes Proformas</h4>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a class="text-muted text-decoration-none" href="{{ route('dashboard.') }}">Accueil</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">Mes Proformas</li>
              </ol>
            </nav>
          </div>
          <div class="col-3">
            <div class="text-center mb-n5">
              <img src="{{ asset('adminAssets/images/breadcrumb/ChatBc.png') }}" alt="modernize-img" class="img-fluid mb-n4">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="widget-content searchable-container list">

      <div class="card card-body">
        <div class="row">
            <div class="col-md-8 col-xl-3">
                @if(session('success'))
                    <div class="alert alert-success text-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger text-danger" role="alert">
                        {!! session('error') !!}
                    </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            </div>
          <div class="col-md-4 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
    
            <a href="{{ route('dashboard.devis.create') }}" class="btn btn-primary">
                <i class="ti ti-users text-white me-1 fs-5"></i> 
                Faire une Proforma
            </a>
              
          </div>
        </div>
      </div>

      @if (Auth::user()->hasRole('Commercial'))
<h5>Mes Proformas</h5>
            <table id="zero_config1" class="table table-striped table-bordered text-nowrap align-middle">
              <thead>
                <!-- start row -->
                <tr>
                    <th>N° Proforma</th>
                    <th>Client</th>
                    <th>Coût</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
                <!-- end row -->
              </thead>
              <tbody>
                @forelse ($mes_devis as $devi)
                <tr>
                    <td>
                        <h6 class="mb-0">{{ $devi->num_proforma }}</h6>
                    </td>
                    <td>{{ $devi->client->nom }}</td>
                    <td>{{ $devi->details->sum('total') }} {{ $devi->devise }}</td>
                    <td>{{ $devi->status ?? 'Non renseigné' }}</td>
                    <td>
                      <div class="action-btn text-center">
                        <a href="{{ route('dashboard.devis.download', $devi->id) }}" class="text-primary me-2" title="Télécharger">
                          <i class="ti ti-download fs-5"></i>
                        </a>
                      

                      <a href="{{ route('dashboard.devis.validate', $devi->id) }}" class="text-success me-2" title="Valider">
                        <i class="ti ti-navigation-check"></i>
                    </a>

                          <a href="{{ route('dashboard.devis.edit', $devi->id) }}" class="text-primary me-2" title="Modifier">
                              <i class="ti ti-pencil fs-5"></i>
                          </a>
                  
                          <form id="delete-form-{{ $devi->id }}" action="{{ route('dashboard.devis.destroy', $devi->id) }}" method="POST" class="d-inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-link text-danger p-0 border-0" title="Supprimer" onclick="confirmDelete({{ $devi->id }})">
                                <i class="ti ti-trash fs-5"></i>
                            </button>
                        </form>
                      </div>
                  </td>
                  
                </tr>


                
                @empty
                    Aucune Proforma enregistrée.
                @endforelse
                
              </tbody>
            
              <tfoot>
                <!-- start row -->
                <tr>
                    <th>N° Proforma</th>
                    <th>Client</th>
                    <th>Coût</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
                <!-- end row -->
              </tfoot>
            </table>
      @endif
      
      @if (Auth::user()->hasRole('Comptable'))
      <div class="card card-body">
        <h5>Liste des Proformas</h5>

        <div class="table-responsive">

          <table id="zero_config2" class="table table-striped table-bordered text-nowrap align-middle">
            <thead>
              <!-- start row -->
              <tr>
                  <th>N° Proforma</th>
                  <th>Client</th>
                  <th>Coût</th>
                  <th>Etabli Par</th>
                  <th>Statut</th>
                  <th>Action</th>
              </tr>
              <!-- end row -->
            </thead>
            <tbody>
              @forelse ($devis as $devi)
              <tr>
                  <td>
                      <h6 class="mb-0">{{ $devi->num_proforma }}</h6>
                  </td>
                  <td>{{ $devi->client->nom }}</td>
                  <td>{{ $devi->details->sum('total') }} {{ $devi->devise }}</td>
                  <td>{{ $devi->user->name }}</td>

                  <td>{{ $devi->status ?? 'Non renseigné' }}</td>
                  <td>
                    <div class="action-btn text-center">
                      <a href="{{ route('dashboard.devis.download', $devi->id) }}" class="text-primary me-2" title="Télécharger">
                        <i class="ti ti-download fs-5"></i>
                      </a>
                      <a href="{{ route('dashboard.factures.refuse', $devi->id) }}" class="text-danger me-2" title="Réfuser">
                        <i class="ti ti-square-rounded-minus"></i>
                      </a>
                    
                
                      </form>
                    </div>
                </td>
                
              </tr>


              
              @empty
                  Aucune Proforma enregistrée.
              @endforelse
              
          </tbody>
          
            <tfoot>
              <!-- start row -->
              <tr>
                  <th>N° Proforma</th>
                  <th>Client</th>
                  <th>Coût</th>
                  <th>Etabli Par</th>
                  <th>Statut</th>
                  <th>Action</th>
              </tr>
              <!-- end row -->
            </tfoot>
          </table>


          

          
        </div>
      </div>

      <div class="card card-body">
        <h5>Mes Proformas Emises</h5>
        <div class="table-responsive">
          <table id="zero_config3" class="mt-5 table table-striped table-bordered text-nowrap align-middle">
            <thead>
              <!-- start row -->
              <tr>
                  <th>N° Proforma</th>
                  <th>Client</th>
                  <th>Coût</th>
                  <th>Statut</th>
                  <th>Action</th>
              </tr>
              <!-- end row -->
            </thead>
            <tbody>
              @forelse ($mes_devis as $devi)
              <tr>
                  <td>
                      <h6 class="mb-0">{{ $devi->num_proforma }}</h6>
                  </td>
                  <td>{{ $devi->client->nom }}</td>
                  <td>{{ $devi->details->sum('total') }} {{ $devi->devise }}</td>
                  <td>{{ $devi->status ?? 'Non renseigné' }}</td>
                  <td>
                    <div class="action-btn text-center">
                      <a href="{{ route('dashboard.devis.download', $devi->id) }}" class="text-primary me-2" title="Télécharger">
                        <i class="ti ti-download fs-5"></i>
                      </a>
                    

                    <a href="{{ route('dashboard.devis.validate', $devi->id) }}" class="text-success me-2" title="Valider">
                      <i class="ti ti-navigation-check"></i>
                  </a>

                        <a href="{{ route('dashboard.devis.edit', $devi->id) }}" class="text-primary me-2" title="Modifier">
                            <i class="ti ti-pencil fs-5"></i>
                        </a>
                
                        <form id="delete-form-{{ $devi->id }}" action="{{ route('dashboard.devis.destroy', $devi->id) }}" method="POST" class="d-inline-block">
                          @csrf
                          @method('DELETE')
                          <button type="button" class="btn btn-link text-danger p-0 border-0" title="Supprimer" onclick="confirmDelete({{ $devi->id }})">
                              <i class="ti ti-trash fs-5"></i>
                          </button>
                      </form>
                    </div>
                </td>
                
              </tr>


              
              @empty
                  Aucune Proforma enregistrée.
              @endforelse
              
          </tbody>
          
            <tfoot>
              <!-- start row -->
              <tr>
                  <th>N° Proforma</th>
                  <th>Client</th>
                  <th>Coût</th>
                  <th>Statut</th>
                  <th>Action</th>
              </tr>
              <!-- end row -->
            </tfoot>
          </table>
        </div>
      </div>
      @endif




      
    </div>
  

</div>
@endsection

@push('scripts')

<script>
  function confirmDelete(devisId) {
      Swal.fire({
          title: "Êtes-vous sûr ?",
          text: "Cette action est irréversible !",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#d33",
          cancelButtonColor: "#3085d6",
          confirmButtonText: "Oui, supprimer !",
          cancelButtonText: "Annuler"
      }).then((result) => {
          if (result.isConfirmed) {
              document.getElementById('delete-form-' + devisId).submit();
          }
      });
  }
</script>

@endpush

