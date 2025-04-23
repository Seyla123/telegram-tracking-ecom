<?php 

namespace SeavSeyla\Banking\Repositories;

use SeavSeyla\Banking\Interfaces\CheckoutRepositoryInterface;
use SeavSeyla\Banking\Models\Checkout;
use Illuminate\Support\Facades\Auth;

class CheckoutRepository implements CheckoutRepositoryInterface
{
    public function create(array $data): Checkout
    {
        return Auth::user()->checkouts()->create($data);
    }
    public function update(Checkout $checkout, array $data): bool
    {
        return $checkout->update($data);
    }
}