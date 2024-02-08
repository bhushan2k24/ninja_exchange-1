<div class="my_datatable">
    <div class="card-datatable table-responsive pt-0 my_datatable2">
        <table class="user-list-table table">
            <thead class="table-light">
                <tr>
                    @foreach ($thead as $thead_th_value)
                        <th class="text-nowrap">{{ $thead_th_value }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody> 
                @if (!$tbody->isEmpty())
                    @foreach ($tbody as $tbody_value)
                        <tr>
                            @foreach ($tbody_value as $tbody_tr_value)
                                <td class="text-nowrap">{!! $tbody_tr_value !!}</td>
                            @endforeach
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="{{ count($thead) }}">No Data Found</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    {{-- pagination --}}
    <div class="justify-content-end mt-1 mx-2">
        {{ $tbody->links() }}
    </div>
</div>
