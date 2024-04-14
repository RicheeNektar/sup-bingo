import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['loading', 'error', 'button']
  static values = {
    url: String,
    bingo: Boolean,
  }

  bingo() {
    this.bingoValue = true;
    this.buttonTarget.disable = true;
  }

  toggleField() {
    if (this.bingoValue) {
      this.buttonTarget.disable = true;
      return;
    }

    this.setLoading(true);
    this.errorTarget.classList.add('d-none');

    (async () => {
      try {
        const r = await fetch(this.urlValue, {
          method: 'POST',
          redirect: 'error',
        });

        const {state, bingo} = await r.json();
        this.buttonTarget.classList[state ? 'add' : 'remove']('active');

        if (bingo) {
          this.dispatch('bingo');
        }

      } catch {

        this.errorTarget.classList.remove('d-none');

      } finally {
        this.setLoading(false);
      }
    })();
  }

  setLoading(loading) {
    this.loadingTarget.classList[loading ? 'remove' : 'add']('d-none');
  }
}
