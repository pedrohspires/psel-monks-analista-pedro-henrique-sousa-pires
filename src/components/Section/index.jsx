import React from 'react';
import NavBar from '../NavBar';
import './Section.scss';

function Section({ slug, title, content }) {

    return (
        <div className='section-container' style={{
            height: slug == "section_1" ? 480 : 200,
            backgroundColor: slug == "section_1" ? "#2D2D2D" : ""
        }}>
            <div className='content'>
                <NavBar />

                <h1>{title}</h1>

                <div
                    className='content-dangerously'
                    dangerouslySetInnerHTML={{ __html: content }}
                />

                <div className='scroll-svg-container'>
                    <img src='src\assets\scroll.svg' />
                </div>
            </div>

            <img src='src\assets\logo-monks.svg' />
        </div>
    )
}

export default Section