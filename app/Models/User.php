<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'city',
        'birthday',
        'cover_photo',
        'pseudo',
        'bio',
        'isActive',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthday' => 'date',
    ];
    /**
     * Un utilisateur peut envoyer plusieurs demandes d'ami.
     */
    public function sentRequests(): HasMany
    {
        return $this->hasMany(Ami::class, 'id_sender');
    }

    /**
     * Un utilisateur peut recevoir plusieurs demandes d'ami.
     */
    public function receivedRequests(): HasMany
    {
        return $this->hasMany(Ami::class, 'id_receiver');
    }
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    // Relation User -> Commentaires
    public function commentaires(): HasMany
    {
        return $this->hasMany(Commantaire::class);
    }
    public function friends()
    {
        return $this->belongsToMany(User::class, 'ami', 'id_sender', 'id_receiver')
            ->wherePivot('status', 'accepted')
            ->select('users.*', 'ami.id_sender as pivot_id_sender', 'ami.id_receiver as pivot_id_receiver') // Specify the same columns
            ->union(
                $this->belongsToMany(User::class, 'ami', 'id_receiver', 'id_sender')
                    ->wherePivot('status', 'accepted')
                    ->select('users.*', 'ami.id_sender as pivot_id_sender', 'ami.id_receiver as pivot_id_receiver') // Specify the same columns
            );
    }
    public function getFriends()
    {
        // Amis où l'utilisateur est l'expéditeur
        $sentFriends = $this->belongsToMany(User::class, 'ami', 'id_sender', 'id_receiver')
            ->wherePivot('status', 'accepted')
            ->select('users.*', 'ami.id_sender as pivot_id_sender', 'ami.id_receiver as pivot_id_receiver')
            ->get();

        // Amis où l'utilisateur est le destinataire
        $receivedFriends = $this->belongsToMany(User::class, 'ami', 'id_receiver', 'id_sender')
            ->wherePivot('status', 'accepted')
            ->select('users.*', 'ami.id_sender as pivot_id_sender', 'ami.id_receiver as pivot_id_receiver')
            ->get();

        // Fusionner les deux collections
        return $sentFriends->merge($receivedFriends);
    }
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Conversations the user is part of
    public function conversations() {
        return $this->hasMany(Conversation::class, 'user_one_id')
            ->orWhere('user_two_id', $this->id);
    }


}
