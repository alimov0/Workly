<!DOCTYPE html>
<html>
<head>
    <title>Yangi Ish Arizasi</title>
    <style>
    
    </style>
</head>
<body>
    <div class="container">
        <h1>Yangi Ish Arizasi</h1>
        <p>Hurmatli {{ $employer->name }},</p>
        <p>Sizning "{{ $application->vacancy->title }}" vakansiyangizga yangi ariza yuborildi:</p>
        
        <div class="details">
            <h3>Ariza haqida:</h3>
            <p><strong>Nomzod:</strong> {{ $application->user->name }}</p>
            <p><strong>Email:</strong> {{ $application->user->email }}</p>
            <p><strong>Qo'shimcha xabar:</strong> {{ $application->cover_letter }}</p>
        </div>
        
        <a href="{{ route('employer.applications.show', $application->id) }}" class="button">
            Ariza Ko'rish
        </a>
    </div>
</body>
</html>