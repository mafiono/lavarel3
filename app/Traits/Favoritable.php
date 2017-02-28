<?php

namespace App\Traits;


use App\Models\Favorite;
use App\User;
use Illuminate\Support\Facades\Auth;

trait Favoritable
{
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function scopeFavoritedBy($query, User $user)
    {
        return $query->whereHas('favorites', function ($query) use ($user) {
            $query->whereUserId($user->id);
        });
    }

    public function scopeFavorited($query)
    {
        $query->favoritedBy(Auth::user());
    }

    public function isFavoritedBy(User $user)
    {
        return $this->favorites()
            ->whereUserId($user->id)
            ->exists();
    }

    public function isFavorite()
    {
        return $this->isFavoritedBy(auth()->user());
    }

    function setFavorite(User $user=null)
    {
        $user = $user ?: Auth::user();

        $this->favorites()->save(
            new Favorite(['user_id' => $user->id])
        );
    }

    function unsetFavorite(User $user=null)
    {
        $user = $user ?: Auth::user();

        $this->favorites()->whereUserId($user->id)->delete();
    }

}