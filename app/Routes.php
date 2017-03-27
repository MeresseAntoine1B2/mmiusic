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

Route::post("/playlist/cree", 'liste@creerplaylist');

/** End default Routes */
