<?php
/**
 * Welcome controller
 *
 * @author David Carr - dave@novaframework.com
 * @version 3.0
 */

namespace App\Controllers;

use App\Core\Controller;

use App\Models\Chanson;
use DB;

use App\Models\Playlist;
use Nova\Support\Facades\Auth;
use Nova\Support\Facades\Redirect;
//use Symfony\Component\Console\Input\Input;
use Nova\Support\Facades\Input;
use View;


/**
 * Sample controller showing 2 methods and their typical usage.
 */
class Liste extends Controller
{
    public function maliste()
    {
        $chansonsPlayer = "";
        $chansons = Chanson::all();
        foreach ($chansons as $c)
        {
            $chansonsPlayer.=$c->nom."<audio controls='controls'><source src='".$c->fichier."'></audio><a href='playlist/ajout?id_chanson=".$c->id."'>Ajouter à</a><br />";
        }

        $playlists = Playlist::all();
        $playlist_txt = "";
        foreach ($playlists as $p)
        {
            $ids_chansons = "";
            $playlist_txt.="$p->nom<ul>";
            $data = DB::select("SELECT * FROM `nova_contient` INNER JOIN nova_chanson ON nova_contient.chanson_id=nova_chanson.id WHERE playlist_id =".$p->id);
            foreach ($data as $item)
            {
                $playlist_txt.="<li>".$item->nom."<audio controls='controls'><source src='".$item->fichier."'></audio></li>";
            }
            $playlist_txt.="</ul>";

        }

        $message = __("$chansonsPlayer");

        return View::make('Liste/Welcome')
            ->shares('title', __('Liste'))
            ->with('welcomeMessage', $message)
            ->with('mesPlaylists', $playlist_txt);
    }

    public function formulaire()
    {
        if(Auth::id() == false)
            return Redirect::to('/login');
        $message = __("
            <form method='post' enctype='multipart/form-data' action='/chanson/cree'>
                <label for='nom'>Nom</label>
                <input type='text' name='nom' placeholder='le titre'><br />
                <label for='style'>style</label>
                <input type='text' name='style' placeholder='Rock, blues, ...'> <br />
                <label for='chanson'>Upload</label>
                <input type='file' name='chanson'>
                
                <input type='submit'>
            </form>      
        
        ");

        return View::make('Welcome/Welcome')
            ->shares('title', __('Welcome'))
            ->with('welcomeMessage', $message);
    }

    public function formulaireplaylist()
    {
        $message = __("
            <form method='post' action='/playlist/cree'>
                <label for='playlist'>Nom</label>
                <input type='text' name='playlist' placeholder='le nom de la playlist'><br />
                <input type='submit'>
            </form>  
            ");

        return View::make('Welcome/Welcome')
            ->shares('title', __('Welcome'))
            ->with('welcomeMessage', $message);
    }

    public function cree()
    {
        if (
            Input::has('nom') &&
            Input::has('style') &&
            Input::hasFile('chanson') &&
            Input::file('chanson')->isValid()
            )
            {
                $file = str_replace(' ', '', Input::file('chanson')->getClientOriginalName());
                $f = Input::file("chanson")->move("assets/chansons/".Auth::user()->username, $file);

            }
        $c = new Chanson();
        $c->nom = Input::get('nom');
        $c->style = Input::get("style");
        $c->fichier = "/".$f;
        $c->utilisateur_id = Auth::id();
        $c->duree = "";
        $c->post_date = date('Y-m-d h:i:s');
        $c->save();
        return Redirect::to('/');
    }

    public function creerplaylist()
    {

        if(Auth::id() == false)
            return Redirect::to('/login');
        if(Input::has('playlist'))
        {
            $p = new Playlist();
            $p-> nom = Input::get('playlist');
            $p->utilisateur_id = Auth::id();
            $p->save();
        }
        return Redirect::to('/');

    }

    public function ajouteplaylist()
    {
        if(Auth::id() == false)
            return Redirect::to('/login');
        if(!isset($_GET["id_chanson"]))
            return Redirect::to('/');
        $playlists = Playlist::all();
        $playlist_txt = "";
        foreach ($playlists as $p)
        {
            if($p->utilisateur_id == Auth::id())
            {
                $playlist_txt.="<a href='/playlist/ajoute?id_playlist=".$p->id."&id_chanson=".$_GET["id_chanson"]."'>$p->nom</a><br />";
            }
        }

        $message = __("");

        return View::make('Liste/Welcome')
            ->shares('title', __('Liste'))
            ->with('welcomeMessage', $message)
            ->with('mesPlaylists', $playlist_txt);
    }

    public function addplaylist()
    {
        if(Auth::id() == false)
            return Redirect::to('/login');
        if(!isset($_GET["id_chanson"]))
            return Redirect::to('/');
        if(!isset($_GET["id_playlist"]))
            return Redirect::to('/');

        /*$p = new Playlist();
        $p-> playlist_id = $_GET["id_playlist"];
        $p-> chanson_id = $_GET["id_chanson"];
        $p->save();*/

        DB::table('contient')->insert(
            array('playlist_id' => $_GET["id_playlist"], 'chanson_id' => $_GET["id_chanson"])
        );
        return Redirect::to('/');
    }
}