interface Props {
    button_type?: "primary" | "secondary" | "info" | "success";
    text: string;
    onClick: () => void;
}

const Button = ({ button_type = "primary", text, onClick }: Props) => {
    return (
        <button type="button" className={"btn btn-" + button_type} onClick={onClick}>{text}</button>
    )
}

export default Button