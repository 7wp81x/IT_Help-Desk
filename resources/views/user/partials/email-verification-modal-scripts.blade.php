<script>
function openVerifyModal() {
    const modal = document.getElementById('emailVerificationModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.getElementById('modalVerificationCode')?.focus();
}

function closeVerifyModal() {
    const modal = document.getElementById('emailVerificationModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

@if ($errors->has('code') || session('open_verify_modal'))
    document.addEventListener('DOMContentLoaded', function() {
        openVerifyModal();
    });
@endif
</script>
