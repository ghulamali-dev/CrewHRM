import React from 'react';

export function IconPDF({ width = 30, height = 31, color = window.CrewHRM.colors['primary'] }) {
    return (
        <svg
            width={width}
            height={height}
            viewBox="0 0 30 31"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                d="M3.75 27.9375H5.625V30.75H3.75C1.64062 30.75 0 29.1094 0 27V4.5C0 2.44922 1.64062 0.75 3.75 0.75H13.418C14.4141 0.75 15.3516 1.16016 16.0547 1.86328L21.3867 7.19531C22.0898 7.89844 22.5 8.83594 22.5 9.83203V17.625H19.6875V10.125H15C13.9453 10.125 13.125 9.30469 13.125 8.25V3.5625H3.75C3.22266 3.5625 2.8125 4.03125 2.8125 4.5V27C2.8125 27.5273 3.22266 27.9375 3.75 27.9375ZM10.3125 21.375H12.1875C13.9453 21.375 15.4688 22.8984 15.4688 24.6562C15.4688 26.4727 13.9453 27.9375 12.1875 27.9375H11.25V29.8125C11.25 30.3398 10.7812 30.75 10.3125 30.75C9.78516 30.75 9.375 30.3398 9.375 29.8125V27V22.3125C9.375 21.8438 9.78516 21.375 10.3125 21.375ZM12.1875 26.0625C12.9492 26.0625 13.5938 25.4766 13.5938 24.6562C13.5938 23.8945 12.9492 23.25 12.1875 23.25H11.25V26.0625H12.1875ZM17.8125 21.375H19.6875C21.2109 21.375 22.5 22.6641 22.5 24.1875V27.9375C22.5 29.5195 21.2109 30.75 19.6875 30.75H17.8125C17.2852 30.75 16.875 30.3398 16.875 29.8125V22.3125C16.875 21.8438 17.2852 21.375 17.8125 21.375ZM19.6875 28.875C20.1562 28.875 20.625 28.4648 20.625 27.9375V24.1875C20.625 23.7188 20.1562 23.25 19.6875 23.25H18.75V28.875H19.6875ZM24.375 22.3125C24.375 21.8438 24.7852 21.375 25.3125 21.375H28.125C28.5938 21.375 29.0625 21.8438 29.0625 22.3125C29.0625 22.8398 28.5938 23.25 28.125 23.25H26.25V25.125H28.125C28.5938 25.125 29.0625 25.5938 29.0625 26.0625C29.0625 26.5898 28.5938 27 28.125 27H26.25V29.8125C26.25 30.3398 25.7812 30.75 25.3125 30.75C24.7852 30.75 24.375 30.3398 24.375 29.8125V26.0625V22.3125Z"
                fill={color}
            />
        </svg>
    );
}
