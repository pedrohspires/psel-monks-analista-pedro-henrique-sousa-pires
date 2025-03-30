import React from 'react'
import './Card.scss'

function Card({ title, content, textBtn }) {
    return (
        <div className='card-container' >
            <div className='content'>
                <h2>{title}</h2>

                <div
                    className='content-dangerously'
                    dangerouslySetInnerHTML={{ __html: content }}
                />

                <div className='btn-container'>
                    <button type='button'>
                        <span>{textBtn}</span>
                    </button>
                </div>
            </div>
        </div>
    )
}

export default Card