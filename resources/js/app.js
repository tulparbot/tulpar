import {EmojiButton} from '@joeattardi/emoji-button';

const picker = new EmojiButton();
const trigger = document.querySelector('#emoji-trigger');

picker.on('emoji', selection => {
    document.querySelector('input#emoji').value = selection.emoji;
});

trigger.addEventListener('click', () => picker.togglePicker(trigger));
