<div class="row">
    <div class="col-sm-12">
        <x-form id="update-software-category-data-form" method="PUT">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-3 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    @lang('superadmin.packages.edit')</h4>
                <div class="row px-3">
                    <div class="col-lg-4 col-md-6">
                        <x-forms.text :fieldLabel="__('app.name')" fieldName="name" fieldRequired="true" fieldId="name"
                                      :fieldValue="$package->name"/>
                    </div>

                </div>


                <x-form-actions>
                    <x-forms.button-primary class="mr-3" id="update-software-category-form" icon="check">@lang('app.update')
                    </x-forms.button-primary>
                    <x-forms.button-cancel :link="route('superadmin.software-category.index')"
                                           class="border-0">@lang('app.cancel')
                    </x-forms.button-cancel>
                </x-form-actions>

            </div>
        </x-form>

    </div>
</div>


<script>

    $(document).ready(function () {
        $('#update-software-category-form').click(function () {
            $.easyAjax({
                url: "{{ route('superadmin.software-category.update', [$package->id]) }}",
                container: '#update-package-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: "#update-software-category-form",
                data: $('#update-software-category-data-form').serialize(),
                success: function () {
                    showTable();
                }
            });
        });

        const showTable = () => {
            try {
                window.LaravelDataTables["package-table"].draw();
            } catch (err) {
            }
        }

        init(RIGHT_MODAL);
    });

</script>
