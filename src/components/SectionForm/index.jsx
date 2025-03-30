import React, { useEffect, useState } from 'react';
import { useForm } from 'react-hook-form';
import { formatPhone } from '../../utils/format';
import { postRequest } from '../../utils/requests';
import './SectionForm.scss';

function Section({ title, content }) {
    const { register, handleSubmit, watch, setValue, reset } = useForm();
    const phoneValue = watch("phone");

    const [sumA, setSumA] = useState(Math.floor(Math.random() * 1000));
    const [sumB, setSumB] = useState(Math.floor(Math.random() * 1000));
    const [formattedPhone, setFormattedPhone] = useState("");

    useEffect(() => {
        if (phoneValue) setFormattedPhone(formatPhone(phoneValue));
        else setFormattedPhone("");
    }, [phoneValue]);

    function handlePhoneChange(e) {
        const formatted = formatPhone(e.target.value);
        setFormattedPhone(formatted);
        setValue("phone_number", formatted);
    };

    async function onSubmit(dataForm) {
        const dataRequest = {
            name: dataForm.name,
            phone_number: formattedPhone,
            email: dataForm.email,
            subject: dataForm.subject,
            numA: sumA,
            numB: sumB,
            result: dataForm.result
        }

        const response = await postRequest('/wp-json/wp/v2/contact', dataRequest);
        if (response.status !== 200 || typeof response == "string") {
            setSumA(Math.floor(Math.random() * 1000));
            setSumB(Math.floor(Math.random() * 1000));
        } else reset();

        const errors = response.data?.errors?.map(x => ({ code: x.code, message: x.message })) || [];

        setInputError(
            "input-name",
            errors?.find(x => x.code == "invalid_name")?.message,
            errors?.map(x => x.code).includes("invalid_name")
        )

        setInputError(
            "input-phone",
            errors?.find(x => x.code == "invalid_phone")?.message,
            errors?.map(x => x.code).includes("invalid_phone")
        )

        setInputError(
            "input-email",
            errors?.find(x => x.code == "invalid_email")?.message,
            errors?.map(x => x.code).includes("invalid_email")
        )

        setInputError(
            "input-subject",
            errors?.find(x => x.code == "invalid_subject")?.message,
            errors?.map(x => x.code).includes("invalid_subject")
        )

        setInputError(
            "input-result",
            errors?.find(x => x.code == "invalid_result")?.message,
            errors?.map(x => x.code).includes("invalid_result")
        )
    }

    function setInputError(inputName, message, hasError) {
        const input = document.getElementsByClassName(inputName)[0];
        const inputMessage = document.getElementsByClassName(`${inputName}-message`)[0];

        if (hasError) {
            input.classList.add("input-error");
            inputMessage.innerHTML = message;
        }
        else {
            input.classList.remove("input-error");
            inputMessage.innerHTML = "";
        }
    }

    return (
        <div className='section-form-container'>
            <img src='src\assets\contact.svg' />

            <div className='content'>
                <div>
                    <h2>{title}</h2>

                    <div
                        className='section-form-content-dangerously'
                        dangerouslySetInnerHTML={{ __html: content }}
                    />

                    <div className='attention'>*Lorem ipsum dolor sit amet consectetur</div>
                </div>

                <form onSubmit={handleSubmit(onSubmit)}>
                    <div className='form-container'>
                        <div className='form-group'>
                            <input className='input-name' placeholder='Nome*' {...register("name")} />
                            <span className='input-name-message input-error-message'></span>
                        </div>

                        <div className='form-group'>
                            <input
                                className='input-phone'
                                placeholder='Telefone*'
                                {...register('phone_number')}
                                value={formattedPhone}
                                onChange={handlePhoneChange}
                            />
                            <span className='input-phone-message input-error-message'></span>
                        </div>

                        <div className='form-group'>
                            <input className='input-email' placeholder='Email*' {...register('email')} />
                            <span className='input-email-message input-error-message'></span>
                        </div>

                        <div className='form-group'>
                            <input className='input-subject' placeholder='Assunto*' {...register('subject')} />
                            <span className='input-subject-message input-error-message'></span>
                        </div>
                    </div>

                    <div className='security-verification'>
                        <span style={{ flex: 1 }}>Verificação de segurança</span>

                        <div style={{ display: "flex", alignItems: "center" }}>
                            <div className='sum-container'>
                                <span>{sumA}</span>
                                <span style={{ color: "#2D2D2D" }}>+</span>
                                <span>{sumB}</span>
                            </div>
                            <span style={{ marginLeft: 32 }}>=</span>
                        </div>

                        <div className='form-group' style={{ flex: 1 }}>
                            <input className='input-result' placeholder='Resultado*' {...register('result')} />
                            <span className='input-result-message input-error-message'></span>
                        </div>
                    </div>

                    <div className='btn-container'>
                        <button type='submit'>
                            <span>Enviar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    )
}

export default Section