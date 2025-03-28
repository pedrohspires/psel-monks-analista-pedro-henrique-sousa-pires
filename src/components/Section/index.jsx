import React from 'react';
import './Section.scss';

function Section({ slug, title, content }) {
    return (
        <div className='section-container' >
            <div className='content'>
                <h2>{title}</h2>

                <div
                    className='content-dangerously'
                    dangerouslySetInnerHTML={{ __html: content }}
                />

                <div className='cards-container'>
                    {/* insert cards here */}
                </div>
            </div>
        </div>
    )
}

export default Section