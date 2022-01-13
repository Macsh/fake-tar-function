<?php
$archive = $argv;
array_shift($archive);
$a = 0;
$b = 0;
$c = 0;

function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

function unarchive($archive){
    global $b;
    foreach($archive as $key => $value){
        $files = file_get_contents($value);
        $filesarray = explode("|", $files);
        array_shift($filesarray);
        foreach($filesarray as $keys => $file){
            $filename = get_string_between($file, "Name => [", "]");
            $filepath = get_string_between($file, "Path => [", "]");
            $filetype = get_string_between($file, "Type => [", "]");
            $filecontent = get_string_between($file, "Content => [", "]");
            if(preg_match("/[a-zA-Z]/i", $filepath)){
                $currentpath = getcwd();
                $fileloc = str_replace(".","", $filepath);
                $filedir = "";
                $filedir = $currentpath.$fileloc;
                if(is_dir($filedir)){
                    $nameoffile = "";
                    $nameoffile = $filedir.$filename;
                    if(file_exists($nameoffile)){
                        if($b == 1 || $b == 3){
                            if($filetype !== "text" && !empty($filetype)){
                                file_put_contents($nameoffile, base64_decode($filecontent));
                            }
                            else{
                                file_put_contents($nameoffile, $filecontent);
                            }  
                        }
                        else{
                            continue;
                        } 
                    } 
                    else{
                        if($filetype !== "text" && !empty($filetype)){
                            $newfile = fopen ($nameoffile, "wb");
                            fwrite($newfile, base64_decode($filecontent));
                            fclose($newfile);
                        }
                        else{
                            $newfile = fopen($nameoffile, "x+");
                            fwrite($newfile, $filecontent);
                            fclose($newfile);
                        } 
                    } 
                } 
                else{
                    mkdir($filedir, 0755, true);
                    $nameoffile = "";
                    $nameoffile = $filedir.$filename;
                    if(file_exists($nameoffile) || empty($nameoffile)){
                        if(file_exists($nameoffile)){
                            if($b == 1 || $b == 3){
                                if($filetype !== "text" && !empty($filetype)){
                                    file_put_contents($nameoffile, base64_decode($filecontent));
                                }
                                else{
                                    file_put_contents($nameoffile, $filecontent);
                                } 
                            }
                            else{
                                continue;
                            } 
                        } 
                    } 
                    else{
                        $nameoffile = "";
                        $nameoffile = $filedir.$filename;
                        if($filetype !== "text" && !empty($filetype)){
                            $newfile = fopen ($nameoffile, "wb");
                            fwrite($newfile, base64_decode($filecontent));
                            fclose($newfile);
                        }
                        else{
                            $newfile = fopen($nameoffile, "x+");
                            fwrite($newfile, $filecontent);
                            fclose($newfile);
                        } 
                    } 
                }  
            }
            else{
                $nameoffile = "";
                $nameoffile = $filename;
                if(file_exists($nameoffile)){
                        if($b == 1 || $b == 3){
                            if($filetype !== "text" && !empty($filetype)){
                                file_put_contents($nameoffile, base64_decode($filecontent));
                            }
                            else{
                                file_put_contents($nameoffile, $filecontent);
                            } 
                        }
                        else{
                            continue;
                        } 
                    } 
                else{
                    if($filetype !== "text" && !empty($filetype)){
                        $newfile = fopen ($nameoffile, "wb");
                        fwrite($newfile, base64_decode($filecontent));
                        fclose($newfile);
                    }
                    else{
                        $newfile = fopen($nameoffile, "x+");
                        fwrite($newfile, $filecontent);
                        fclose($newfile);
                    } 
                }  
            } 
        }  
    }
    echo "\n\nArchive désarchivé avec succès !\n\n";
    confirm_delete();
} 

function user_input($archive){
    $isokey = "";
    global $b;
    global $a;
    foreach($archive as $key => $value){
        $files = file_get_contents($value);
        $filesarray = explode("|", $files);
        array_shift($filesarray);
        foreach($filesarray as $keys => $file){
            $filename = get_string_between($file, "Name => [", "]");
            $filepath = get_string_between($file, "Path => [", "]");
            $filetype = get_string_between($file, "Type => [", "]");
            $filecontent = get_string_between($file, "Content => [", "]");
            if(preg_match("/[a-zA-Z]/i", $filepath)){
                $currentpath = getcwd();
                $fileloc = str_replace(".","", $filepath);
                $filedir = "";
                $filedir = $currentpath.$fileloc;
                if(is_dir($filedir)){
                    $nameoffile = "";
                    $nameoffile = $filedir.$filename;
                    $isokey .= "ispasokey";
                    if(file_exists($nameoffile) || empty($nameoffile)){
                        $isokey .= "ispasokey";
                    } 
                } 
                else{
                    $nameoffile = "";
                    $nameoffile = $filedir.$filename;
                    if(file_exists($nameoffile) || empty($nameoffile)){
                        $isokey .= "ispasokey";
                    } 
                    else{
                    } 
                }  
            }
            else{
                $nameoffile = "";
                $nameoffile = $filename;
                if(file_exists($nameoffile) || empty($nameoffile)){
                    $isokey .= "ispasokey";
                } 
                else{
                }  
            } 
        }  
    } 
    if($b == 3 || $b == 4){
        unarchive($archive);
    } 
    elseif (!empty($isokey)){
        echo "Des fichiers ou dossiers du même nom existent déjà, que voulez vous faire ?\n";
        echo "
        1. Écraser\n
        2. Ne pas écraser\n
        3. Écraser pour tous (ne plus redemander)\n
        4. Ne pas écraser pour tous (ne plus redemander)\n
        5. Arrêter et quitter\n\n";
        $a = readline("\nFaites votre choix : ");
        return $a;
    }
    else{
        unarchive($archive);
    } 
} 

user_input($archive);

if($a == 5){
    $a = 0;
} 
elseif($a == 3){
    $a = 0;
    $b = 3;
    unarchive($archive);
} 
elseif($a == 4){
    $a = 0;
    $b = 4;
    unarchive($archive);
} 
elseif($a == 2){
    $a = 0;
    $b = 2;
    unarchive($archive);
} 
elseif($a == 1){
    $a = 0;
    $b = 1;
    unarchive($archive);
} 

function confirm_delete(){
    global $c;
    echo "\nVoulez-vous supprimer l'archive existante ?\n";
        echo "
        1. Supprimer\n
        2. Ne pas Supprimer\n\n";
        $c = readline("\nFaites votre choix : ");
        return $c;
}

function delete_archive(){
    global $archive;
    foreach($archive as $key => $value){
        unlink($value);
    }
    echo "\nArchive supprimé avec succès !\n";
}

if($c == 1){
    $c = 0;
    delete_archive();
} 
elseif($c == 2){
    $c = 0;
    return false;
} 
?>