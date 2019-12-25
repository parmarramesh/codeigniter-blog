<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Posts extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        include APPPATH . 'third_party/mpdf/vendor/autoload.php';

        include APPPATH . 'third_party/vendor/autoload.php';
    }
    public function index($offset = 0)
    {
        if (!islogin()) {
            $this->load->view('templates/header');
            $this->load->view('auth/login');
            $this->load->view('templates/footer');
        } else {
            $config['base_url'] = base_url() . 'posts/index/';
            $config['total_rows'] = $this->db->where('user_id', $this->session->userdata('userId'))->from('posts')->count_all_results();
            $config['per_page'] = 3;
            $config['uri_sagment'] = 3;
            $config['attributes'] = array('class' => 'pagination');

            $this->pagination->initialize($config);

            $data['title']= "Latest Post";
            $data['posts'] = $this->post_model->get_posts(false, $config['per_page'], $offset, $this->page_data['langId']);
            $this->load->view('templates/header');
            $this->load->view('posts/index', $data);
            $this->load->view('templates/footer');
        }
    }

    public function view($slug = null)
    {
        if (!islogin()) {
            $this->load->view('templates/header');
            $this->load->view('auth/login');
            $this->load->view('templates/footer');
        } else {
            $data['post'] = $this->post_model->get_posts($slug, false, false, $this->page_data['langId']);

            $data['comments'] = $this->post_model->get_comment($data['post']['id']);
            if (empty($data['post'])) {
                show_404();
            }
            $data['title'] = $data['post']['title'];

            $this->load->view('templates/header');
            $this->load->view('posts/view_blog', $data);
            $this->load->view('templates/footer');
        }
    }

    public function create()
    {
        if (!islogin()) {
            $this->load->view('templates/header');
            $this->load->view('auth/login');
            $this->load->view('templates/footer');
        } else {
            $data['title'] = "Create Post";
            $data['categories'] = $this->post_model->get_categories($this->page_data['langId']);
            $this->form_validation->set_rules('title', 'Title', 'required');
            $this->form_validation->set_rules('body', 'Body', 'required');
            $this->form_validation->set_rules('category', 'Category', 'required');

            if ($this->form_validation->run() == false) {
                $this->load->view('templates/header');
                $this->load->view('posts/create', $data);
                $this->load->view('templates/footer');
            } else {
                $config['upload_path'] = './assets/images/blogs';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size'] = '2048';
                $config['max_width'] = '2000';
                $config['max_height'] = '2000';
                $new_name = time();
                $config['file_name'] = $new_name;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('userfile')) {
                    $error = array('error' => $this->upload->display_errors());
                    $post_image = 'noimage.png';
                } else {
                    $extension = pathinfo($_FILES["userfile"]["name"], PATHINFO_EXTENSION);
                    $post_image = $new_name.'.'.$extension;
                }

                $this->post_model->create_post($post_image);
                $this->session->set_flashdata('blog_created', 'Blog created successfully');
                redirect('blogs');
            }
        }
    }

    public function delete($id)
    {
        if (!islogin()) {
            $this->load->view('templates/header');
            $this->load->view('auth/login');
            $this->load->view('templates/footer');
        } else {
            $this->post_model->delete_post_comment($id);
            $this->post_model->delete_post($id);
            redirect('blogs');
        }
    }

    public function edit($slug)
    {
        if (!islogin()) {
            $this->load->view('templates/header');
            $this->load->view('auth/login');
            $this->load->view('templates/footer');
        } else {
            $data['post'] = $this->post_model->get_posts($slug);

            $data['categories'] = $this->post_model->get_categories($this->page_data['langId']);
            if (empty($data['post'])) {
                show_404();
            }
            $data['title'] = 'Update Blogs';

            $this->load->view('templates/header');
            $this->load->view('posts/edit', $data);
            $this->load->view('templates/footer');
        }
    }

    public function update()
    {
        if (!islogin()) {
            $this->load->view('templates/header');
            $this->load->view('auth/login');
            $this->load->view('templates/footer');
        } else {
            if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
                $config['upload_path'] = './assets/images/blogs';
                $config['allowed_types'] = 'gif|jpg|jpeg|png';
                $config['max_size'] = '2048';
                $config['max_width'] = '2000';
                $config['max_height'] = '2000';
                $new_name = time();
                $config['file_name'] = $new_name;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('userfile')) {
                    $error = array('error' => $this->upload->display_errors());
                    $post_image = 'noimage.png';
                } else {
                    $extension = pathinfo($_FILES["userfile"]["name"], PATHINFO_EXTENSION);
                    $post_image = $new_name.'.'.$extension;
                }
            } else {
                $post_image = '';
            }
            $this->post_model->update_blog($post_image);
            $this->session->set_flashdata('blog_updated', 'Blog updated successfully');
            redirect('blogs');
        }
    }

    public function commentAdd()
    {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('comment', 'Comment', 'required');
        if ($this->form_validation->run() == false) {
            if (form_error('name')) {
                $output = array('code' => 0, 'message'=> strip_tags(form_error('name')));
                echo json_encode($output);
            } elseif (form_error('email')) {
                $output = array('code' => 0, 'message'=> strip_tags(form_error('email')));
                echo json_encode($output);
            } elseif (form_error('comment')) {
                $output = array('code' => 0, 'message'=> strip_tags(form_error('comment')));
                echo json_encode($output);
            } else {
                $output = array('code' => 0, 'message'=> 'Some error occured, Please try again!');
                echo json_encode($output);
            }
        } else {
            $id = $this->input->post('pid');
            $last_comment = $this->post_model->create_comment($id);
            $output = array('code' => 1, 'data' => $last_comment, 'message'=> 'Your comment posted successfully');
            echo json_encode($output);
        }
    }

    public function exportToWord($slug)
    {
        if (!islogin()) {
            $this->load->view('templates/header');
            $this->load->view('auth/login');
            $this->load->view('templates/footer');
        } else {
            $data['post'] = $this->post_model->get_posts($slug, false, false, $this->page_data['langId']);

            $data['comments'] = $this->post_model->get_comment($data['post']['id']);
            if (empty($data['post'])) {
                show_404();
            }
            $data['title'] = $data['post']['title'];

            $this->report($data);

            $this->load->view('templates/header');
            $this->load->view('posts/view_blog', $data);
            $this->load->view('templates/footer');
        }
    }

    public function RemoveSpecialChar($value)
    {
        $title = str_replace(array('\'', '&', ';', '!', 'amp'), '', $value);
        return $title;
    }

    public function report($data)
    {
        $pw = new \PhpOffice\PhpWord\PhpWord();
        $section = $pw->addSection();
        $report_html = $this->load->view('posts/report', $data, true);
        $report_html = $this->RemoveSpecialChar($report_html);
        $logo_url = base_url() . "assets/images/logo.jpg";
        $header = $section->addHeader();
        $header->addImage($logo_url, array('width'=>50, 'height'=>50, 'align' => 'center'));
        // $test =	$section->addImage($logo_url, array('width'=>50, 'height'=>50, 'align' => 'center'));
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $report_html, true, false);
        $textbox = $section->addTextBox(
            array(
                    'align' => 'center',
                    'marginTop' => -30,
                    'background-color' => 'E74C3C',
                    'width' => 450,
                    'height' => 150,
                    'borderSize' => 1,
                    'borderColor' => '#00aada',
                )
            );
        $textbox->addText(htmlspecialchars(''));
        /* [SAVE FILE ON THE SERVER] */

        $report_name = url_title($data['post']['title'], '_', true);
        $file ="application/views/reports/blogs/" . $report_name . "_report.docx";
        $report_generated = $pw->save($file, "Word2007");
        // echo $temp; exit;
        $file_html = "application/views/reports/blogs/" . $report_name . "_report.html";
        if (!($fq = fopen($file_html, "wb"))) {
            die("Can't open");
        }
        fwrite($fq, $report_html);
        fclose($fq);
        if ($report_generated) {
            $this->session->set_flashdata('alert-type', 'success');
            $this->session->set_flashdata('alert_message', 'Blog exported successfully');
        } else {
            $this->session->set_flashdata('alert-type', 'danger');
            $this->session->set_flashdata('alert_message', 'Error on exporting blog, Please try again');
        }
    }

    public function exportReport()
    {
        if (!islogin()) {
            $this->load->view('templates/header');
            $this->load->view('auth/login');
            $this->load->view('templates/footer');
        } else {
            $data['title']= "Latest Post";
            $data['posts'] = $this->post_model->get_posts_for_report(false, false, false, $this->page_data['langId']);
            $this->generateReport($data['posts']);
            $this->load->view('templates/header');
            $this->load->view('posts/index', $data);
            $this->load->view('templates/footer');
        }
    }

    public function generateReport($data)
    {
        $spreadsheet = new Spreadsheet(); // instantiate Spreadsheet

        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        //   $spreadsheet->getProperties()->setCreator('miraimedia.co.th')
        // ->setLastModifiedBy('Cholcool')
        // ->setTitle('how to export data to excel use phpspreadsheet in codeigniter')
        // ->setSubject('Generate Excel use PhpSpreadsheet in CodeIgniter')
        // ->setDescription('Export data to Excel Work for me!');
        // add style to the header
        $styleArray = array(
   'font' => array(
     'bold' => true,
   ),
   'alignment' => array(
     'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
     'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
   ),
   'borders' => array(
       'bottom' => array(
           'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
           'color' => array('rgb' => '333333'),
       ),
   ),
   'fill' => array(
     'type'       => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
     'rotation'   => 90,
     'startcolor' => array('rgb' => '0d0d0d'),
     'endColor'   => array('rgb' => 'f2f2f2'),
   ),
 );
        $spreadsheet->getActiveSheet()->getStyle('A1:F1')->applyFromArray($styleArray);
        // auto fit column to content
        foreach (range('A', 'F') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(false);
        }

        $fields = $data->list_fields();

        // print_r($fields);exit;

        // set the names of header cells
        $sheet->setCellValue('A1', $fields[0]);
        $sheet->setCellValue('B1', $fields[1]);
        $sheet->setCellValue('C1', $fields[2]);
        $sheet->setCellValue('D1', $fields[3]);
        $sheet->setCellValue('E1', $fields[4]);
        $sheet->setCellValue('F1', $fields[5]);

        // Add some data
        $x = 2;
        foreach ($data->result_array() as $get) {
            $sheet->setCellValue('A'.$x, $get[$fields[0]]);
            $sheet->setCellValue('B'.$x, $get[$fields[1]]);
            $sheet->setCellValue('C'.$x, $get[$fields[2]]);
            $sheet->setCellValue('D'.$x, $get[$fields[3]]);
            $sheet->setCellValue('E'.$x, $get[$fields[4]]);
            $sheet->setCellValue('F'.$x, $get[$fields[5]]);
            $x++;
        }
        $writer = new Xlsx($spreadsheet); // instantiate Xlsx

        $filename = 'report_'.time(); // set filename for excel file to be exported

        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');	// download file
    }

    public function exportToPdf($slug)
    {
        if (!islogin()) {
            $this->load->view('templates/header');
            $this->load->view('auth/login');
            $this->load->view('templates/footer');
        } else {
            $data['post'] = $this->post_model->get_posts($slug, false, false, $this->page_data['langId']);

            $data['comments'] = $this->post_model->get_comment($data['post']['id']);
            if (empty($data['post'])) {
                show_404();
            }
            $data['title'] = $data['post']['title'];

            $this->pdfReport($data);

            $this->load->view('templates/header');
            $this->load->view('posts/view_blog', $data);
            $this->load->view('templates/footer');
        }
    }

    public function pdfReport($data)
    {
        $mpdf = new \Mpdf\Mpdf();
        $report_html = $this->load->view('posts/pdf_report', $data, true);
        $report_html = $this->RemoveSpecialChar($report_html);

        $report_name = url_title($data['post']['title'], '_', true);
        $file = $report_name . "_report.pdf";
        $mpdf->WriteHTML($report_html);
        $mpdf->Output();
        // $mpdf->Output($file, 'D');
    }
}
