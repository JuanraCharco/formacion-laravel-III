<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class FileManagerController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('File manager')) {
            return abort(401);
        }

        return view('admin.fileManager.index');
    }


    // Define a function to output files in a directory
    public function outputFiles($path,$extension_arr){
        $allow_file_extensions = $extension_arr;
        // Check directory exists or not
        if(file_exists($path) && is_dir($path)){
            // Scan the files in this directory
            $result = scandir($path);

            // Filter out the current (.) and parent (..) directories
            $files = array_diff($result, array('.', '..'));

            if(count($files) > 0){
                // Loop through retuned array
                foreach($files as $file){
                    if(is_file("$path/$file")){
                        // Display filename
                        $extension = explode('.',$file)[ count(explode('.',$file))-1 ];
                        if (in_array($extension,$allow_file_extensions) || in_array('*',$allow_file_extensions))
                            echo '<li data-jstree=\'{"icon":"far fa-file"}\'> '.$file.'</li>';
                        //echo $file . "*";
                        //echo $path."\n";
                    } else if(is_dir("$path/$file")){
                        // Recursively call the function if directories found
                        echo '<li data-jstree=\'{"icon":"fas fa-folder"}\'> '.$file.'<ul>';
                        $this->outputFiles("$path/$file",$extension_arr);
                        echo '</ul></li>';
                    }
                }
            } else{
                //echo "ERROR: No files found in the directory.";
            }
        } else {
            //echo "ERROR: The directory does not exist.";
        }
    }

    public function getFiles(Request $request) {
        $extension_arr = $request->input('extension_arr');
        return $this->outputFiles('/var/www/filemanager/',$extension_arr);
    }

    private function seo_friendly_string($string){
        $string = str_replace(array('[\', \']'), '_', $string);
        $string = preg_replace('/\[.*\]/U', '_', $string);
        $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '_', $string);
        $string = htmlentities($string, ENT_COMPAT, 'utf-8');
        $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
        $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '_', $string);
        return strtolower(trim($string, '_'));
    }



}
