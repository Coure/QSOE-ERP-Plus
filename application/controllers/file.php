<?php
class File extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
    }

    public function upload()
    {
        // Check if 'userfile' is submitted.
        if ( ! isset($_FILES['userfile']))
        {
            $error = 'Please select a file to upload:';
        }
        else
        {
            // Set upload path and type.
            $config['upload_path'] = VIEWPATH.'uploads';
            $config['allowed_types'] = 'xlsx|xls';

            // Load upload class
            $this->load->library('upload', $config);

            // Upload file from field 'userfile'.
            if ( ! $this->upload->do_upload('userfile'))
            {
                // Error "Unallowed file type" if file size = 0
                $error = $this->upload->display_errors();
            }
            else
            {
                $error = 'Upload successfully.';
            }
        }
        $this->load->view('header', ['title' => 'File Upload']);
        $this->load->view('upload', ['error' => $error]);
        $this->load->view('footer');
    }
}
?>