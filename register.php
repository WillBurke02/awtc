<?php
$mysqli = require __DIR__ . "/database.php";

// Retrieve a random CAPTCHA from the database
$result = $mysqli->query("SELECT * FROM captcha ORDER BY RAND() LIMIT 1");
$captcha = $result->fetch_assoc();

// Construct the full URL to the CAPTCHA image
$captchaImagePath = $captcha['captcha_link'];
?>

<!DOCTYPE html>
<html lang="en">

<title>Registration</title>
<meta name="description" content="Registration page for Tasty Bites website.">
<meta name="keywords" content="Registration, Sign up, Create account, Tasty Bites, Food website">
<script src="https://unpkg.com/react@16/umd/react.production.min.js"></script>
<script src="https://unpkg.com/react-dom@16/umd/react-dom.production.min.js"></script>
<script src="https://unpkg.com/babel-standalone@6.15.0/babel.min.js"></script>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="registerstyle.css">

<body>
<div id="root"></div>

<script type="text/babel">

class NameForm extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      name: '',
      email: '',
      telno: '',
      pass: '',
      nameError: '',
      emailError: '',
      telnoError: '',
      passError: ''
    };

    this.handleChange = this.handleChange.bind(this);
  }

  handleChange(event) {
    // Destructure name and value from the event target
    const { name, value } = event.target;
    // Update state with the new value and reset any existing error for the input
    this.setState({
      [name]: value,
      [name + "Error"]: ''
    });
    // Validate input 
    if (name === "name") {
      var regex = /^[a-zA-Z\s]*$/;
      if (!value.match(regex)) {
          this.setState({
              nameError: "Your name should contain only alphabet letters."
          });
          return;
      }
    } else if (name === "email") {
      var emailRegex = /^([\w.%+-]+)@([\w-]+\.)+([\w]{2,})$/i;
      if (!value.match(emailRegex)) {
          this.setState({
              emailError: "Please enter a valid email address."
          });
          return;
      }
    } else if (name === "telno") {
      var telnoRegex = /^\d{11}$/;
      if (!value.match(telnoRegex)) {
          this.setState({
              telnoError: "Please enter a valid 11-digit phone number."
          });
          return;
      }
    }
  }

  render() {
    return (
      <div class="wrapper">
        <form action="process-register.php" method="post" onSubmit={this.handleSubmit}>
          <h1> Register </h1>
          <div className="input-box">
            <input type="text" placeholder="Name" id="name" name="name" value={this.state.name} onChange={this.handleChange} required />
            <i className='bx bxs-user-rectangle'></i>
            <div style={{ color: 'red' }}>{this.state.nameError}</div>
          </div>

          <div className="input-box">
            <input type="email" placeholder="Email" id="email" name="email" value={this.state.email} onChange={this.handleChange} />
            <i className='bx bxs-envelope'></i>
            <div style={{ color: 'red' }}>{this.state.emailError}</div>
          </div>

          <div className="input-box">
            <input type="tel" placeholder="Phone Number" id="telno" name="telno" value={this.state.telno} onChange={this.handleChange} />
            <i className='bx bxs-phone'></i>
            <div style={{ color: 'red' }}>{this.state.telnoError}</div>
          </div>

          <div className="input-box">
            <input type="password" placeholder="Password" id="pass" name="pass" value={this.state.pass} onChange={this.handleChange} />
            <i className='bx bxs-lock-alt'></i>
            <div style={{ color: 'red' }}>{this.state.passError}</div>
          </div>

          <input type="submit" class="btn" value="Submit" />
          <div class="login-link">
            <p>Already have an account? <a href="login.php" class="login-link">Sign in here</a></p>
          </div>

          <div class="food">
            <p>Not interested? <a href="menu.php">Browse our food selection here!</a> </p>
          </div>
        </form>
      </div>
    );
  }
}


ReactDOM.render(
  <NameForm />,
  document.getElementById('root')
);

</script>

</body>
</html>
