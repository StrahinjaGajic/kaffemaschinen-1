<?php

namespace App\Http\Controllers\Admin;

use App\DataGrid\Facade as DataGrid;
use App\Models\Database\Subscriber;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;

class NewsletterController extends AdminController
{
    public function index()
    {
        $dataGrid = DataGrid::model(Subscriber::query())
            ->column('id', ['sortable' => true])
            ->column('email', ['sortable' => true, 'label' => __('lang.email')])
            ->linkColumn(__('lang.delete'), [], function ($model) {
                return "<form id='admin-newsletter-destroy-" . $model->id . "'
                                            method='POST'
                                            action='" . route('admin.newsletter.destroy', $model->id) . "'>
                                        <input name='_method' type='hidden' value='DELETE' />
                                        " . csrf_field() . "
                                        <a href='#'
                                            onclick=\"jQuery('#admin-newsletter-destroy-$model->id').submit()\"
                                            >". __('lang.delete') ."</a>
                                    </form>";
            })
            ->setPagination(100);

        return view('admin.newsletter.index')->with('dataGrid', $dataGrid);
    }

    public function destroy($id)
    {
        Subscriber::destroy($id);
        return redirect()->route('admin.newsletter.index');
    }

    public function csvView(Request $request)
    {
        $subscribers = Subscriber::all();
        view()->share('subscribers', $subscribers);

        if ($request->has('download')) {
            $spreadsheet = new Spreadsheet();

            $title = 'Newsletter';
            
            $spreadsheet->getProperties()->setCreator('Schoengebraucht')
                ->setLastModifiedBy('Schoengebraucht')
                ->setTitle($title)
                ->setSubject($title)
                ->setDescription($title)
                ->setCategory($title);

            $spreadsheet->setActiveSheetIndex(0);
            $sheet = $spreadsheet->getActiveSheet();


            $sheet->setCellValue('A1', 'ID')
                ->setCellValue('B1', 'Email');

            $count = 1;
            foreach ($subscribers as $subscriber) {
                $count++;
                $sheet->setCellValue('A'.$count, $subscriber->id)
                    ->setCellValue('B'.$count, $subscriber->email);
            }

            $sheet->setTitle($title);

            header('Content-Type: text/csv');
            header('Content-Disposition: attachment;filename="'.$title.'.csv"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');

            $writer = IOFactory::createWriter($spreadsheet, 'Csv');
            $writer->setDelimiter(';');
            $writer->save('php://output');
        } else {

            return view('admin.newsletter.pdfview');
        }

    }
}