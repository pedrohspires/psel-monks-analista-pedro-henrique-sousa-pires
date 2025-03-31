import React, { useState } from 'react';
import './NavBar.scss';

function NavBar() {
    const [menuOpen, setMenuOpen] = useState(false);

    return (
        <div>
            <div className='navbar'>
                <img className='monks-logo' src='src\assets\monks.svg' />

                <img
                    className='sandwich-menu'
                    src='src\assets\sandwich-menu.svg'
                    onClick={() => setMenuOpen(!menuOpen)}
                />

                <ul className='category-container'>
                    <li>Categoria 1</li>
                    <li>Categoria 2</li>
                    <li>Categoria 3</li>
                </ul>
            </div>

            <div
                style={{
                    transform: !menuOpen ? "translate(0, -100%)" : ""
                }}
                className='menu'
            >
                <div className='navbar-mobile'>
                    <img className='monks-logo' src='src\assets\monks.svg' />

                    <img
                        className='sandwich-menu'
                        src='src\assets\sandwich-menu.svg'
                        onClick={() => setMenuOpen(!menuOpen)}
                    />
                </div>

                <ul>
                    <li>Categoria 1</li>
                    <li>Categoria 2</li>
                    <li>Categoria 3</li>
                </ul>

                <div className='back-img' onClick={() => setMenuOpen(!menuOpen)}>
                    <img src='src\assets\back.svg' />
                </div>
            </div>
        </div>
    )
}

export default NavBar