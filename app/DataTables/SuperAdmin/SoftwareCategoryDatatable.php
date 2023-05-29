<?php

namespace App\DataTables\SuperAdmin;

use App\DataTables\BaseDataTable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use App\Models\SuperAdmin\SoftwareCategory;

class SoftwareCategoryDataTable extends BaseDataTable
{

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {

        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                $action = '<div class="task_view">

                <div class="dropdown">
                    <a class="task_view_more d-flex align-items-center justify-content-center dropdown-toggle" type="link"
                        id="dropdownMenuLink-' . $row->id . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-options-vertical icons"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink-' . $row->id . '" tabindex="0">';

                    $action .= '<a class="dropdown-item openRightModal" href="' . route('superadmin.software-category.edit', $row->id) . '" >
                        <i class="fa fa-edit mr-2"></i>
                        ' . trans('app.edit') . '
                    </a>';


                    $action .= '<a class="dropdown-item delete-table-row" href="javascript:;" data-toggle="tooltip"  data-category-id="' . $row->id . '">
                            <i class="fa fa-trash mr-2"></i>
                            ' . trans('app.delete') . '
                        </a>';

                $action .= '</div>
                </div>
            </div>';

                return $action;
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SoftwareCategory $model)
    {
        $searchText = request('searchText');

        return $model->newQuery()
            ->where(function ($query) use ($searchText) {
                $query->where('name', 'like', "%$searchText%");
            });
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->setBuilder('superadmin-table', 0)
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["superadmin-table"].buttons().container()
                    .appendTo("#table-actions")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    })
                }',
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            '#' => ['data' => 'id', 'name' => 'id', 'visible' => true],
            __('app.name') => ['data' => 'name', 'name' => 'name', 'title' => __('app.name')],
            Column::computed('action', __('app.action'))
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->width(150)
                ->addClass('text-right pr-20')
        ];
    }

}
