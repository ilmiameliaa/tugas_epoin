<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<f>
  <a class="nav.link" href="{{ route('siswa.index') }}">Data Siswa</a>
  <a href="{{ route ('logout') }}" onclick="event.preventDefault(); document.getElemenById('logout-form'). submit ();">Logout</a>
  @csrf
</form> 
<h1> Dashboard Admin</h1>
@if ($message = Session::get('success'))
<p>You are logged in!</p>
@endif 
  
</body>

<footer>

</footer>

</html>