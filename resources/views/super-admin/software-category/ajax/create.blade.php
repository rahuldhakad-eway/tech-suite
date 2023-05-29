<div class="row">
    <div class="col-sm-12">
        <x-form id="save-software-category-data-form">
            <div class="add-client bg-white rounded">
                <h4 class="mb-0 p-3 f-21 font-weight-normal text-capitalize border-bottom-grey">
                    @lang('superadmin.software-category.create')</h4>
                <div class="row px-3">
                    <div class="col-lg-4 col-md-6">
                        <x-forms.text :fieldLabel="__('app.name')" fieldName="name" fieldRequired="true"
                                      fieldId="name"/>
                    </div>

                </div>

                <x-form-actions>
                    <x-forms.button-primary class="mr-3" id="save-software-category-form" icon="check">@lang('app.save')
                    </x-forms.button-primary>
                    <x-forms.button-cancel :link="route('superadmin.packages.index')"
                                           class="border-0">@lang('app.cancel')
                    </x-forms.button-cancel>
                </x-form-actions>

            </div>
        </x-form>

    </div>
</div>


<script>

    $(document).ready(function () {
        $('#save-software-category-form').click(function () {
            $.easyAjax({
                url: "{{ route('superadmin.software-category.store') }}",
                container: '#save-software-category-data-form',
                type: "POST",
                disableButton: true,
                blockUI: true,
                buttonSelector: "#save-software-category-form",
                data: $('#save-software-category-data-form').serialize(),
            });
        });

        init(RIGHT_MODAL);
    });


</script>
