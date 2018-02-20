@extends($layout)

@section('content')
    <div id="site-config">
        <label class="control-label" for="search">Search</label><input class="form-control" id="search" v-model="search">
        <br>
        <table class="table table-condensed">
            <thead></thead>
                <tr>
                    <th>Key</th>
                    <th>Value</th>
                    <th>Type</th>
                    <th></th>
                </tr>
            <tbody>
                <tr v-for="item in filteredList">
                    <td><small>@{{ item.key }}</small></td>
                    <td><input class="form-control input-sm" type="text" v-model="item.value"></td>
                    <td>
                        <select class="form-control input-sm" v-model="item.type">
                            <option value="">Default</option>
                            @foreach ($types as $type)
                                <option value="{{ $type }}">{{ ucwords(str_replace('_', ' ', $type)) }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <button class="btn btn-default btn-sm" @click="update(item)">Update</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection

@push('script')
    <script>
        var siteConfig = new Vue({
            el: '#site-config',
            data: {
                list: {!! json_encode($list) !!},
                search: '',
            },
            methods: {
                update: function (item) {
                    axios.post('{{ route('admin.site_config.store') }}', item)
                        .then(function (response) {
                            item = response.data;
                        })
                }
            },
            computed: {
                filteredList: function () {
                    var vm = this;

                    if (vm.search === '') {
                        return vm.list;
                    }

                    return vm.list.filter(function (item) {
                        return item.key.match(new RegExp('.*' + vm.search +'.*'));
                    });
                }
            }
        });
    </script>
@endpush
