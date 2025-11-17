package com.ajea.Elearno.controllers;

import com.ajea.Elearno.models.AppUser;
import com.ajea.Elearno.models.dtos.LoginRequest;
import com.ajea.Elearno.models.dtos.RegisterRequest;
import com.ajea.Elearno.service.AppUserService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.Optional;

@CrossOrigin(origins = "*", maxAge = 3600)
@RestController
@RequestMapping("/api/auth")
public class AuthController {

    private final AppUserService appUserService;

    @Autowired
    public AuthController(AppUserService appUserService) {
        this.appUserService = appUserService;
    }

    @PostMapping("/register")
    public ResponseEntity<?> registerUser(@RequestBody RegisterRequest registerRequest) {
        try {
            AppUser registeredUser = appUserService.registerUser(registerRequest.getUsername(), registerRequest.getPassword());
            return new ResponseEntity<>(registeredUser, HttpStatus.CREATED);
        } catch (RuntimeException e) {
            return new ResponseEntity<>(e.getMessage(), HttpStatus.BAD_REQUEST);
        }
    }

    @PostMapping("/login")
    public ResponseEntity<?> loginUser(@RequestBody LoginRequest loginRequest) {
        Optional<AppUser> appUserOptional = appUserService.loginUser(loginRequest.getUsername(), loginRequest.getPassword());
        if (appUserOptional.isPresent()) {
            return new ResponseEntity<>(appUserOptional.get(), HttpStatus.OK);
        } else {
            return new ResponseEntity<>("Invalid credentials", HttpStatus.UNAUTHORIZED);
        }
    }
}
