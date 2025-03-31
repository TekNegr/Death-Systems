<div>
    <h1>Hello {{ Auth::user() ? Auth::user()->name : 'User' }}</h1>
</div>
