package com.ajea.Elearno.models;

import com.ajea.Elearno.models.enums.LearningStyle;
import com.ajea.Elearno.models.enums.UserRole;
import jakarta.persistence.Entity;
import jakarta.persistence.EnumType;
import jakarta.persistence.Enumerated;
import jakarta.persistence.Id;
import lombok.Data;


@Entity
@Data
public class AppUser {

    @Id
    private Long id;

    private String username;

    private String password;

    @Enumerated(EnumType.STRING)
    private LearningStyle learningStyle;

    @Enumerated(EnumType.STRING)
    private UserRole role;
}
