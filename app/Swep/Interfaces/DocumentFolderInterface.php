<?php

namespace App\Swep\Interfaces;
 


interface DocumentFolderInterface {

	public function fetchAll($request);

	public function store($request);

	// public function update($request, $slug);

	// public function destroy($slug);

	// public function findBySlug($slug);

	public function globalFetchAll();
		
}