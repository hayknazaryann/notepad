<?php

namespace App\Services;


use App\Helpers\PDF2Text;

class FileService
{
    public function content($file)
    {
        $ext = $file->getClientOriginalExtension();
        $content = '';
        switch ($ext) {
            case 'doc':
                $content = $this->read_doc($file->path());
                break;
            case 'docx':
                $content = $this->read_docx($file->path());
                break;
            case 'pdf':
                $a = new PDF2Text();
                $a->setFilename($file->path());
                $a->decodePDF();
                $content = $a->output();
                break;
            case 'txt':
                $content = $file->getContent();
        }


        return $content;
    }

    private function read_doc($path) {
        $fileHandle = fopen($path, "r");
        $line = @fread($fileHandle, filesize($path));
        $lines = explode(chr(0x0D),$line);
        $outtext = "";
        foreach($lines as $thisline)
        {
            $pos = strpos($thisline, chr(0x00));
            if (($pos !== FALSE)||(strlen($thisline)==0))
            {
            } else {
                $outtext .= $thisline." ";
            }
        }
        $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","",$outtext);
        return $outtext;
    }


    private function read_docx($path){

        $striped_content = '';
        $content = '';

        $zip = zip_open($path);

        if (!$zip || is_numeric($zip)) return false;

        while ($zip_entry = zip_read($zip)) {

            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

            if (zip_entry_name($zip_entry) != "word/document.xml") continue;

            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

            zip_entry_close($zip_entry);
        }// end while

        zip_close($zip);

        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);
        $striped_content = strip_tags($content);

        return $striped_content;
    }

}
