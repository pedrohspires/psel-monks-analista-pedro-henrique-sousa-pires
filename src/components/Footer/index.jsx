import React from 'react'
import './Footer.scss'

function Footer() {
    return (
        <div className='footer-container'>
            <div className='social'>
                <a href='https://www.instagram.com/madebymonks/' target='_blank'>
                    <img src='src\assets\instagram.svg' />
                </a>
                <a href='https://wa.me/+5585991224393?text=Contratado!' target='_blank'>
                    <img src='src\assets\whatsapp.svg' />
                </a>
                <a href='https://x.com/madebymonks_' target='_blank'>
                    <img src='src\assets\x.svg' />
                </a>
                <a href='https://www.facebook.com/BR.Monks/?locale=pt_BR' target='_blank'>
                    <img src='src\assets\facebook.svg' />
                </a>
            </div>

            <div className='infos'>
                <span className='title'>Lorem ipsum dolor sit amet</span>

                <div className='categories'>
                    <span>Lorem ipsum</span>
                    <span>Lorem ipsum</span>
                    <span>Lorem ipsum</span>
                    <span>Lorem ipsum</span>
                </div>
            </div>
        </div>
    )
}

export default Footer