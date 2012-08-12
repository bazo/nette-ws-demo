<?php

/**
 * Homepage presenter.
 *
 * @author     John Doe
 * @package    MyApplication
 */
class HomepagePresenter extends BasePresenter
{

	public function renderDefault()
	{
		$this->template->anyVariable = 'any value';
		//$section = $this->getSession()->getSection('section');
		//$section->kokot = 'pica';
		
		//echo $section->kokot;
		//if(!$this->user->isLoggedIn())
		//{
			//$this->user->login();
		//}
		
	}
	
	public function handleTest()
	{
		$this->invalidateControl();
		//$this->terminate();
	}

}
