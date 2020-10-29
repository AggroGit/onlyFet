<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Plan;
use App\User;
//

class Plan extends Model
{
    // cuando se actualiza un precio entonces deberíamos cambiar de plan todos los uaurios.
    public function updateThePlans($key,$suscriptions)
    {
      // lo primero de todo es crear la nueva suscripción
      $new = new Plan();
      $new->payForEvery = $key;
      $new->price = $suscriptions;
      $new->oldPrice = $this->price;
      $new->user_id = auth()->user()->id;
      $new->save();
      // ahora en stripe
      if($id = $new->createNewPlanStripe()) {
        $new->previuous_stripe_id = $this->stripe_tarifa_id;
        $new->stripe_tarifa_id = $id;
      }
      //
      $this->save();
      $new->save();
      $this->delete();
      // ahora deberiamos actualizar a todos sus usuarios
      // PENDING
    }

    // nos crea un nuevo plan en STRIPE
    public function createNewPlanStripe()
    {
      try {
        //
        $interval = $this->getInterval($this->payForEvery);
        //
        $sus = auth()->user()->stripe()->prices->create([
          'unit_amount' => $this->price*100,
          'currency' => 'eur',
          'recurring' => [
            'interval'        => 'month',
            'interval_count'  => $interval
          ],
          'product' => auth()->user()->id,
        ]);
        //
        return $sus->id;
      } catch (\Exception $e) {
        return false;
      }


    }

    // lo llamamos cuando creamos un nuevo plan de 0, sin nada que actualizar
    public function newPlanInStripe()
    {
      if($id = $this->createNewPlanStripe()) {
        $this->stripe_tarifa_id = $id;
        $this->save();
        return true;
      }
      return false;
    }

    // usuarios suscritos al plan
    public function usersSuscribed()
    {
      return $this->belongsToMany('App\User', 'users_plans');
    }

    // usuario quien hace el plan
    public function user()
    {
      return $this->belongsTo('App\User');
    }

    // check if is user suscribed
    public function isUser(User $user)
    {
      if ($this->usersSuscribed()->find($user->id)) {
        return true;
      }
      return false;
    }

    // suscribimos un usuario
    public function suscribeUser(User $user) {
      if (!$this->isUser($user)) {
        // suscribimos en Stripe
        try {
          $user->newSubscription('default', $this->stripe_tarifa_id)->add();
        } catch (\Exception $e) {
          return false;
        }
        // add to plans
        $this->usersSuscribed()->save($user);
        return $this->save();
      }
      return false;
    }



    public function getInterval($value)
    {
      switch ($value) {
        case 'month1':
          return 1;
          break;
        case 'month3':
        return 3;
        break;
        case 'month6':
        return 6;
        break;
        case 'mont12':
        return 12;
        break;
        default:
          return 12;
          break;
      }
    }
}
