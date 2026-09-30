@include('errors.brand', [
    'code' => 405,
    'title' => "That link can't be opened this way",
    'message' => 'This address only works from a form on the website. Go back and try again, or start from the homepage.',
    'search' => true,
    'retry' => false,
])
