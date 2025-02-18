@extends('administration.layouts.master')

@section('content')
<div class="container-fluid">
           
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
      <div class="card-body px-4 py-3">
        <div class="row align-items-center">
          <div class="col-9">
            <h4 class="fw-semibold mb-8">Mes Factures</h4>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a class="text-muted text-decoration-none" href="{{ route('dashboard.') }}">Accueil</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">Mes Factures</li>
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
            </div>
          <div class="col-md-4 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
    
            <a href="" class="btn btn-primary">
                <i class="ti ti-users text-white me-1 fs-5"></i> 
                Faire une facture
            </a>
              
          </div>
        </div>
      </div>

      


      <div class="card card-body">
        <div class="table-responsive mb-5">
            <h3>
                Liste des devis
            </h3>
            <table id="zero_config" class="table table-striped table-bordered text-nowrap align-middle">
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
                    <td>{{ $devi->details->sum('total') }}</td>
                    <td class=""><strong>{{ $devi->user->name }}</strong></td>
                    <td>{{ $devi->status ?? 'Non renseigné' }}</td>

                    <td>
                        <a href="{{ route('dashboard.factures.refuse', $devi->id) }}" class="text-primary me-2" title="Réfuser">
                            <i class="ti ti-square-rounded-x"></i>
                        </a>
                        <a href="" class="text-primary me-2" title="Telecharger">
                          <i class="ti ti-download fs-5"></i>
                      </a>
                        <a href="{{ route('dashboard.factures.create', $devi->id) }}" class="text-primary me-2" title="Etablir la facture">
                          <i class="ti ti-clipboard-list"></i>
                      </a>
                      
                    </td>
                  
                </tr>


                
                @empty
                    Aucune Devis
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

        {{-- <div class="table-responsive mt-5">
          <h3>
            Mes factures etabli
          </h3>
            <table id="zero_config" class="table table-striped table-bordered text-nowrap align-middle">
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
                @forelse ($myFactures as $myFacture)
                <tr>
                    <td>
                        <h6 class="mb-0">{{ $myFacture->num_proforma }}</h6>
                    </td>
                    @if($myFacture->devis->client)
                        {{ $myFacture->devis->client->nom }}
                    @else
                        Client non défini
                    @endif 
                    <td>{{ $myFacture->details->sum('total') }}</td>
                    <td class=""><strong>{{ $devi->user->name }}</strong></td>
                    <td>{{ $myFacture->status ?? 'Non renseigné' }}</td>

                    <td>
                       
                      
                    </td>
                  
                </tr>


                
                @empty
                    Aucune Facture enregistrée.
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
        </div> --}}
      </div>
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

