@extends('base')

@section('links')
  <title>Leaderboard - CodeCharacter</title>
  <link rel="stylesheet" href="stylesheets/leaderboard.css">
  <link rel="stylesheet" href="stylesheets/notifications.css">
@endsection

@section('main')

  <div class="row">
    <div class="twelve columns">
      <h2 class="text-center" id="main-heading">Leaderboard</h2>
      <hr />
    </div>
  </div>
  <div class="row">
    <div class="twelve columns callout-container primary table-container">
      <table>
        <tbody>
          <tr>
            <th>
              Team Name
            </th>
            <th>
              Level
            </th>
            <th>
              Score
            </th>
          </tr>
    @foreach ($leaderboard as $user)
        @if ( $user->currentLevel === 0 )
            <tr>
                <td>
                {{$user->teamName}}
                </td>
                <td>
                1
                </td>
                <td>
                {{$user->score}}
                </td>
            </tr>
        @elseif ( $user->score === 0 && $user->currentLevel === env('MAX_LEVEL'))
            <tr>
                <td>
                {{$user->teamName}}
                </td>
                <td>
                {{$user->currentLevel}}
                </td>
                <td>
                {{$user->score}}
                </td>
            </tr>
        @else
            <tr>
                <td>
                {{$user->teamName}}
                </td>
                <td>
                {{$user->currentLevel}}
                </td>
                <td>
                {{$user->score}}
                </td>
            </tr>
        @endif
    @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection

