import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['bingo'];

  bingo() {
    this.bingoTarget.classList.add('show', 'd-block');
  }
}