<?php

namespace App\DataTables;

use App\Models\KurikulumBukti;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class KurikulumBuktiDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<KurikulumBukti> $query
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('level', function (KurikulumBukti $bukti) {
                $colors = [
                    'PRODI' => 'bg-warning text-dark',
                    'FIKES' => 'bg-success',
                    'UNIV'  => 'bg-primary',
                ];
                $bg = $colors[$bukti->level] ?? 'bg-secondary';
                return '<span class="badge ' . $bg . '">' . $bukti->level . '</span>';
            })
            ->editColumn('status', function (KurikulumBukti $bukti) {
                $colors = [
                    'Tersedia'       => 'text-success',
                    'Tidak Ada'      => 'text-danger',
                    'Belum Memenuhi' => 'text-warning',
                ];
                $color = $colors[$bukti->status] ?? 'text-secondary';
                return '<strong class="' . $color . '">' . $bukti->status . '</strong>';
            })
            ->editColumn('link', function (KurikulumBukti $bukti) {
                return $bukti->link ? '<a href="' . $bukti->link . '" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-link-45deg"></i> Link</a>' : '-';
            })
            ->addColumn('action', function (KurikulumBukti $bukti) {
                $btn = '<div class="d-flex gap-1 justify-content-center">';
                $btn .= '<button type="button" class="btn btn-sm btn-warning text-white" onclick="editBukti(' . $bukti->id . ')" title="Edit"><i class="bi bi-pencil-fill" style="font-size:11px;"></i></button>';
                
                $btn .= '<form action="' . route('kurikulum.bukti.destroy', $bukti->id) . '" method="POST" class="m-0" onsubmit="return confirm(\'Hapus bukti ini?\');">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="bi bi-trash-fill" style="font-size:11px;"></i></button>
                        </form>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['level', 'status', 'link', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(KurikulumBukti $model): QueryBuilder
    {
        return $model->newQuery()
            ->where('kurikulum_id', $this->kurikulum_id)
            ->select(['id', 'kurikulum_id', 'nama_bukti', 'level', 'status', 'link', 'pic', 'deadline', 'catatan']);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('kurikulumbukti-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->parameters([
                'dom' => '<"d-flex justify-content-between align-items-center mb-3"l<"text-end"Bf>>rt<"d-flex justify-content-between mt-3"ip>',
                'buttons' => ['excel', 'csv', 'print'],
                'language' => ['url' => '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json'],
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('No.')->searchable(false)->orderable(false)->width(30)->addClass('text-center'),
            Column::make('nama_bukti')->title('NAMA BUKTI'),
            Column::make('level')->title('LEVEL')->addClass('text-center'),
            Column::make('status')->title('STATUS'),
            Column::make('link')->title('LINK')->addClass('text-center'),
            Column::make('pic')->title('PIC'),
            Column::make('deadline')->title('DEADLINE'),
            Column::make('catatan')->title('CATATAN'),
            Column::computed('action')->title('AKSI')->exportable(false)->printable(false)->width(80)->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'KurikulumBukti_' . date('YmdHis');
    }
}
