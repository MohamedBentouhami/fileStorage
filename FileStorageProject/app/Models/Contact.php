<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class Contact extends Model
{
    use HasFactory;


    protected $fillable = [];

    /**
     * Pas de champs created_at et updated_at
     */
    protected $timestamp = false;

    const PENDING = 0;
    const CONFIRM = 1;


    public function user()
    {

        return $this->belongsTo(User::class);
    }

    public function files()
    {

        return $this->BelongsToMany(File::class, 'shared_files');
    }

    public static function getContact($id)
    {

        $contacts = User::select(array('name', 'users.id'))->join('contacts', 'contacts.user_id', '=', 'users.id')
            ->where([
                ["contact_id", "=", $id],
            ])
            ->get();
        return $contacts;
    }

    /**
     * delete contact from de user to his contact and the reverse
     */
    public static function deleteContact($idContact)
    {
        DB::delete(
            "delete from contacts where user_id = ? and contact_id = ?",
            [
                Auth::id(), $idContact
            ]
        );
        DB::delete(
            "delete from contacts where user_id = ? and contact_id = ?",
            [
                $idContact, Auth::id()
            ]
        );
        DB::delete(
            "delete from shared_files where id_owner = ? and contact_id = ?",
            [
                Auth::id(), $idContact
            ]
        );
        DB::delete(
            "delete from shared_files where id_owner = ? and contact_id = ?",
            [
                $idContact, Auth::id()
            ]
        );
    }
}
