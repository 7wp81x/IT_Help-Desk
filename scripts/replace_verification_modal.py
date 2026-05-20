from pathlib import Path

files = [
    Path(r'c:/Users/THIS PC/Herd/it-helpdesk-system/resources/views/user/dashboard.blade.php'),
    Path(r'c:/Users/THIS PC/Herd/it-helpdesk-system/resources/views/user/profile/index.blade.php'),
]

for path in files:
    text = path.read_text(encoding='utf-8')
    start_marker = '<div id="emailVerificationModal"'
    end_marker = '\n    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">'

    start = text.find(start_marker)
    if start == -1:
        print(f'START NOT FOUND in {path}')
        continue
    end = text.find(end_marker, start)
    if end == -1:
        print(f'END NOT FOUND in {path}')
        continue

    replacement = '        @include(\'partials.email-verification-modal\')' + end_marker
    new_text = text[:start] + replacement + text[end:]
    path.write_text(new_text, encoding='utf-8')
    print(f'Updated {path}')
