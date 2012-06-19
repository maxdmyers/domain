<?php

use Domain\Models\Account;

class Domain_Account_Controller extends Domain_Base_Controller {
	
	public function __construct()
	{
		$this->model = new Account;
	}

	/**
	 * Get all accounts
	 *
	 * @return Response
	 */
	public function get_list()
	{
		$this->options = array(
			'sort_by' => 'created_at',
		);

		$this->settings = array(
			'sortable' => array(
				'accounts' => array(
					'name',
					'email',
					'created_at',
					'updated_at'
				)
			),
			'searchable' => array(
				'page_lang' => array(
					'name',
					'email'
				)
			)
		);

		$this->includes = array('roles', 'roles.lang', 'language');

		return $this->get_multiple(Input::all());
	}

	/**
	 * Get account by id
	 *
	 * @return Response
	 */
	public function get_read($id)
	{
		$this->includes = array('roles', 'language');

		return $this->get_single($id);
	}

	/**
	 * Add account
	 *
	 * @return Response
	 */
	public function post_create()
	{
		$account = $this->model();

		$account::$rules['password'] = 'required';
		$account::$rules['email'] = 'required|email|unique:accounts,email';

		$account::$accessible[] = 'password';

		$sync = array(
			'roles' => Input::get('roles')
		);

		return $this->create_single(Input::all(), $sync);
	}

	/**
	 * Edit account
	 *
	 * @return Response
	 */
	public function put_update($id)
	{
		// Find the account we are updating
		$account = $this->model($id);

		// If the password is set, we allow it to be updated
		if(Input::get('password') !== '') $account::$accessible[] = 'password';

		$sync = array(
			'roles' => Input::get('roles')
		);
			
		return $this->update_single(Input::all(), $sync);
	}

	/**
	 * Delete account
	 *
	 * @return Response
	 */
	public function delete_delete($id)
	{
		$this->model($id);

		$this->delete_single();
	}

}