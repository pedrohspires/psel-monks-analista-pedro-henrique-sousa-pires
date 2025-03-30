import React from 'react';
import NavBar from '../NavBar';
import './SectionPrimary.scss';

function Section({ slug, title, content }) {

    return (
        <div className='section-primary-container' >
            <div className='section-primary-content'>
                <NavBar />

                <h1>{title}</h1>

                <div
                    className='section-primary-content-dangerously'
                    dangerouslySetInnerHTML={{ __html: content }}
                />

                <div className='section-primary-scroll-svg-container'>
                    <img src='src\assets\scroll.svg' />
                </div>
            </div>

            <img src='src\assets\logo-monks.svg' />
        </div>
    )
}

export default Section