<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="k-m-l-s-table">
            <thead>
            <tr>
                <th>Kml File</th>
                <th colspan="3">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($kmls as $kml)
                <tr>
                    <td>{{ $kml->kml_file }}</td>
                    <td  style="width: 120px">
                        {!! Form::open(['route' => ['kmls.destroy', $kml->id], 'method' => 'delete']) !!}
                        <div class='btn-group'>
                            {{--  <a href="{{ route('kmls.show', [$kml->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ route('kmls.edit', [$kml->id]) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-edit"></i>
                            </a>  --}}
                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $kmls])
        </div>
    </div>
</div>
