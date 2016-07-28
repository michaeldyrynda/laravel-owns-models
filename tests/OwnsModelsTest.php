<?php

use Iatstuti\Database\Support\OwnsModels;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\Model;

class OwnsModelsTest extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        $manager = new Manager;
        $manager->addConnection([
            'driver'   => 'sqlite',
            'database' => ':memory:',
        ]);

        $manager->setAsGlobal();
        $manager->bootEloquent();

        $manager->schema()->create('users', function ($table) {
            $table->increments('id');
            $table->string('name');
        });

        $manager->schema()->create('posts', function ($table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('title');
        });

        $manager->schema()->create('posts_custom', function ($table) {
            $table->increments('id');
            $table->integer('owner')->unsigned();
            $table->string('title');
        });
    }

    /** @test */
    public function it_loosely_owns_the_given_model()
    {
        $user = User::create(['name' => 'Michael Dyrynda']);
        $post = Post::create(['user_id' => $user->id, 'title' => 'Ensure a user owns a post with a loose comparison']);

        $this->assertTrue($user->owns($post));
        $this->assertFalse($user->doesntOwn($post));
    }

    /** @test */
    public function it_strictly_owns_the_given_model()
    {
        $user = User::create(['name' => 'Michael Dyrynda']);
        $post = PostStrict::create(['user_id' => $user->id, 'title' => 'Ensure a user owns a post with a strict comparison']);

        $this->assertTrue($user->owns($post, 'user_id', true));
        $this->assertFalse($user->doesntOwn($post, 'user_id', true));
    }

    /** @test */
    public function it_loosely_doesnt_own_the_given_model()
    {
        $userOne = User::create(['name' => 'Michael Dyrynda']);
        $userTwo = User::create(['name' => 'Jacob Bennett']);
        $post    = Post::create(['user_id' => $userOne->id, 'title' => 'Ensure a user does not own a post with a loose comparison']);

        $this->assertFalse($userTwo->owns($post));
        $this->assertTrue($userTwo->doesntOwn($post));
    }

    /** @test */
    public function it_strictly_doesnt_own_the_given_model()
    {
        $userOne = User::create(['name' => 'Michael Dyrynda']);
        $userTwo = User::create(['name' => 'Jacob Bennett']);
        $post    = Post::create(['user_id' => $userOne->id, 'title' => 'Ensure a user does not own a post with a loose comparison']);

        $this->assertFalse($userTwo->owns($post, 'user_id', true));
        $this->assertTrue($userTwo->doesntOwn($post, 'user_id', true));
    }

    /** @test */
    public function it_owns_the_given_model_with_a_custom_foreign_key()
    {
        $user = User::create(['name' => 'Michael Dyrynda']);
        $post = PostCustom::create(['owner' => $user->id, 'title' => 'Ensure a user owns a post with a custom foreign key']);

        $this->assertTrue($user->owns($post, 'owner'));
        $this->assertFalse($user->doesntOwn($post, 'owner'));
    }

    /** @test */
    public function it_doesnt_own_the_given_model_with_a_custom_foreign_key()
    {
        $userOne = User::create(['name' => 'Michael Dyrynda']);
        $userTwo = User::create(['name' => 'Jacob Bennett']);
        $post    = PostCustom::create(['owner' => $userOne->id, 'title' => 'Ensure a user does not own a post with a custom foreign key']);

        $this->assertFalse($userTwo->owns($post, 'owner'));
        $this->assertTrue($userTwo->doesntOwn($post, 'owner'));
    }
}

class User extends Model
{
    use OwnsModels;

    public $timestamps = false;

    protected $fillable = ['name'];
}

class Post extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'title'];
}

class PostStrict extends Model
{
    protected $table = 'posts';

    public $timestamps = false;

    protected $fillable = ['user_id', 'title'];

    protected $casts = ['user_id' => 'int'];
}

class PostCustom extends Model
{
    protected $table = 'posts_custom';

    public $timestamps = false;

    protected $fillable = ['owner', 'title'];
}
