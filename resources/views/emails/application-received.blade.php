<h2>Yangi ariza yuborildi!</h2>

<p><strong>Arizachi:</strong> {{ $application->user->name }}</p>
<p><strong>Ish nomi:</strong> {{ $application->vacancy->title }}</p>
<p><strong>Xat:</strong> {{ $application->cover_letter }}</p>
<p>📄 Rezyume: <a href="{{ asset('storage/'.$application->resume_file) }}">Yuklab olish</a></p>
