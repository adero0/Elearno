package com.ajea.Elearno.service;

import com.ajea.Elearno.models.AppUser;
import com.ajea.Elearno.models.enums.UserRole;
import com.ajea.Elearno.repository.AppUserRepo;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;

import java.util.Optional;

@Service
public class AppUserService {
//    static int i;

    private final AppUserRepo appUserRepo;
    private final PasswordEncoder passwordEncoder;

    @Autowired
    public AppUserService(AppUserRepo appUserRepo, PasswordEncoder passwordEncoder) {
        this.appUserRepo = appUserRepo;
        this.passwordEncoder = passwordEncoder;
    }

    public AppUser registerUser(String username, String password) {
        if (username == null || username.trim().isEmpty()) {
            throw new RuntimeException("Username cannot be empty");
        }
        System.out.println(username + " <- usernam");
        System.out.println(password + " <- passw");
        if (appUserRepo.findByUsername(username).isPresent()) {
            throw new RuntimeException("Username already exists");
        }
        AppUser appUser = new AppUser();
        appUser.setUsername(username);
        appUser.setPassword(passwordEncoder.encode(password));
        appUser.setRole(UserRole.USER);
//        appUser.setId((long) i++);
        return appUserRepo.save(appUser);
    }

    public Optional<AppUser> loginUser(String username, String password) {
        Optional<AppUser> appUserOptional = appUserRepo.findByUsername(username);
        if (appUserOptional.isPresent()) {
            AppUser appUser = appUserOptional.get();
            if (passwordEncoder.matches(password, appUser.getPassword())) {
                return appUserOptional;
            }
        }
        return Optional.empty();
    }

    public AppUser updateUserLearningStyle(Long userId, int visualCount, int auditoryCount, int kinestheticCount) {
        AppUser appUser = appUserRepo.findById(userId)
                .orElseThrow(() -> new RuntimeException("User not found"));

        appUser.setVisual(appUser.getVisual() + visualCount);
        appUser.setAuditory(appUser.getAuditory() + auditoryCount);
        appUser.setKinesthetic(appUser.getKinesthetic() + kinestheticCount);
        appUser.setHasTakenQuiz(true);

        return appUserRepo.save(appUser);
    }
}