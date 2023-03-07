<?php

namespace Vanguard\Repositories\PotentialProduct;

use Carbon\Carbon;
use Vanguard\User;
use \Laravel\Socialite\Contracts\User as SocialUser;

interface PotentialProductRepository
{
    /**
     * Paginate registered users.
     *
     * @param $perPage
     * @param null $search
     * @param null $status
     * @return mixed
     */
    public function paginate($perPage, $search = null, $status = null);

    /**
     * Find user by its id.
     *
     * @param $id
     * @return null|User
     */
    public function find($id);

    /**
     * Create new user.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update user specified by it's id.
     *
     * @param $id
     * @param array $data
     * @return User
     */
    public function update($id, array $data);

    /**
     * Delete user with provided id.
     *
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * Number of users in database.
     *
     * @return mixed
     */
    public function count();

    /**
     * save by collection
     * @param string $site
     * @return mixed
     */
    public function fetchProducts();

}