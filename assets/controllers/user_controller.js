import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['loading', 'select']
  static values = {
    username: String,
    url: String,
  }

  changeRole() {
    this.setLoading(true);
    this.setSuccess(null);
    (async () => {
      try {
        const r = await fetch(this.urlValue, {
          method: 'POST',
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: new URLSearchParams({
            username: this.usernameValue,
            role: this.selectTarget.value,
          }).toString(),
          redirect: "error",
        });
        this.setSuccess(r.ok);
      } catch {
        this.setSuccess(false);
      } finally {
        this.setLoading(false);
      }
    })();
  }

  setLoading(loading) {
    this.loadingTarget.classList[loading ? 'remove' : 'add']('d-none');
  }

  setSuccess(success) {
    this.selectTarget.classList.remove('is-valid', 'is-invalid');
    if (success !== null) {
      this.selectTarget.classList.add(success ? 'is-valid' : 'is-invalid');
    }
  }
}
