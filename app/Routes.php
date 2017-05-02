<?php
/**
 * Routes - all standard Routes are defined here.
 *
 * @author David Carr - dave@daveismyname.com
 * @author Virgil-Adrian Teaca - virgil@giulianaeassociati.com
 * @version 3.0
 */


/** Define static routes. */

// The default Routing
Route::get('/',       'Liste@maliste');
Route::get('/subpage', 'Welcome@subPage');

Route::get("/chanson/nouvelle", 'Liste@formulaire');
Route::post("/chanson/cree", 'Liste@cree');

Route::get("/playlist/nouvelle", 'Liste@formulaireplaylist');
Route::post("/playlist/cree", 'Liste@creerplaylist');
Route::get("/playlist/ajout", 'Liste@ajouteplaylist');
Route::get("/playlist/ajoute", 'Liste@addplaylist');

/** End default Routes */
